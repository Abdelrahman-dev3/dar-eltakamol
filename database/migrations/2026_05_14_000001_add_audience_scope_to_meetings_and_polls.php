<?php

use App\Models\Contributor;
use App\Services\ParticipantAudienceResolver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            if (!Schema::hasColumn('meetings', 'audience_scope')) {
                $table->string('audience_scope', 50)->default(ParticipantAudienceResolver::SCOPE_MANUAL)->after('date');
            }

            if (!Schema::hasColumn('meetings', 'audience_committee')) {
                $table->string('audience_committee')->nullable()->after('audience_scope');
            }

            if (!Schema::hasColumn('meetings', 'audience_category_id')) {
                $table->foreignId('audience_category_id')->nullable()->after('audience_committee')->constrained('categories')->nullOnDelete();
            }
        });

        Schema::table('polls', function (Blueprint $table) {
            if (!Schema::hasColumn('polls', 'audience_scope')) {
                $table->string('audience_scope', 50)->default(ParticipantAudienceResolver::SCOPE_MANUAL)->after('zoom_meeting_id');
            }

            if (!Schema::hasColumn('polls', 'audience_committee')) {
                $table->string('audience_committee')->nullable()->after('audience_scope');
            }

            if (!Schema::hasColumn('polls', 'audience_category_id')) {
                $table->foreignId('audience_category_id')->nullable()->after('audience_committee')->constrained('categories')->nullOnDelete();
            }
        });

        $this->backfillMeetings();
        $this->backfillPolls();
    }

    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            if (Schema::hasColumn('polls', 'audience_category_id')) {
                $table->dropConstrainedForeignId('audience_category_id');
            }

            if (Schema::hasColumn('polls', 'audience_committee')) {
                $table->dropColumn('audience_committee');
            }

            if (Schema::hasColumn('polls', 'audience_scope')) {
                $table->dropColumn('audience_scope');
            }
        });

        Schema::table('meetings', function (Blueprint $table) {
            if (Schema::hasColumn('meetings', 'audience_category_id')) {
                $table->dropConstrainedForeignId('audience_category_id');
            }

            if (Schema::hasColumn('meetings', 'audience_committee')) {
                $table->dropColumn('audience_committee');
            }

            if (Schema::hasColumn('meetings', 'audience_scope')) {
                $table->dropColumn('audience_scope');
            }
        });
    }

    private function backfillMeetings(): void
    {
        DB::table('meetings')
            ->select('id', 'name')
            ->orderBy('id')
            ->each(function ($meeting): void {
                $userIds = DB::table('meeting_user')
                    ->where('meeting_id', $meeting->id)
                    ->pluck('user_id');

                DB::table('meetings')
                    ->where('id', $meeting->id)
                    ->update($this->inferAudience($userIds, $meeting->name));
            });
    }

    private function backfillPolls(): void
    {
        DB::table('polls')
            ->select('id', 'title', 'question')
            ->orderBy('id')
            ->each(function ($poll): void {
                $userIds = DB::table('poll_users')
                    ->where('poll_id', $poll->id)
                    ->pluck('user_id');

                DB::table('polls')
                    ->where('id', $poll->id)
                    ->update($this->inferAudience($userIds, trim(($poll->title ?? '') . ' ' . ($poll->question ?? ''))));
            });
    }

    private function inferAudience(Collection $userIds, ?string $label = null): array
    {
        $targetIds = $this->normalizeIds($userIds);
        $label = (string) $label;

        if ($targetIds->isEmpty()) {
            return ['audience_scope' => ParticipantAudienceResolver::SCOPE_MANUAL];
        }

        if (str_contains($label, 'لجان') || str_contains($label, 'لجنة')) {
            return $this->committeeAudienceFromTargets($targetIds);
        }

        if (str_contains($label, 'مجلس')) {
            return ['audience_scope' => ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS];
        }

        if ($targetIds->all() === $this->boardMemberUserIds()->all() || ($targetIds->count() > 1 && $targetIds->diff($this->boardMemberUserIds())->isEmpty())) {
            return ['audience_scope' => ParticipantAudienceResolver::SCOPE_BOARD_MEMBERS];
        }

        $committeeAudience = $this->committeeAudienceFromTargets($targetIds, false);
        if ($committeeAudience !== null) {
            return $committeeAudience;
        }

        if ($targetIds->all() === $this->allContributorUserIds()->all()) {
            return ['audience_scope' => ParticipantAudienceResolver::SCOPE_ALL_CONTRIBUTORS];
        }

        if ($targetIds->all() === $this->allUserIds()->all()) {
            return ['audience_scope' => ParticipantAudienceResolver::SCOPE_ALL_USERS];
        }

        return ['audience_scope' => ParticipantAudienceResolver::SCOPE_MANUAL];
    }

    private function committeeAudienceFromTargets(Collection $targetIds, bool $fallbackToFirst = true): ?array
    {
        foreach (Contributor::committeeMembershipOptions() as $committee) {
            $committeeUserIds = $this->committeeUserIds($committee);

            if ($committeeUserIds->isNotEmpty() && $targetIds->diff($committeeUserIds)->isEmpty()) {
                return [
                    'audience_scope' => ParticipantAudienceResolver::SCOPE_COMMITTEE,
                    'audience_committee' => $committee,
                ];
            }
        }

        if ($fallbackToFirst) {
            return [
                'audience_scope' => ParticipantAudienceResolver::SCOPE_COMMITTEE,
                'audience_committee' => Contributor::committeeMembershipOptions()[0] ?? null,
            ];
        }

        return null;
    }

    private function allUserIds(): Collection
    {
        return $this->normalizeIds(DB::table('users')->pluck('id'));
    }

    private function allContributorUserIds(): Collection
    {
        return $this->normalizeIds(DB::table('contributors')->whereNotNull('user_id')->pluck('user_id'));
    }

    private function boardMemberUserIds(): Collection
    {
        return $this->normalizeIds(
            DB::table('contributors')
                ->where('is_board_member', true)
                ->whereNotNull('user_id')
                ->pluck('user_id')
        );
    }

    private function committeeUserIds(string $committee): Collection
    {
        return $this->normalizeIds(
            DB::table('contributors')
                ->whereNotNull('user_id')
                ->get(['user_id', 'committee_memberships'])
                ->filter(function ($contributor) use ($committee): bool {
                    $memberships = json_decode((string) $contributor->committee_memberships, true);

                    return is_array($memberships) && in_array($committee, $memberships, true);
                })
                ->pluck('user_id')
        );
    }

    private function normalizeIds(Collection $ids): Collection
    {
        return $ids
            ->filter()
            ->map(function ($id): int {
                if (is_numeric($id)) {
                    return (int) $id;
                }

                if (is_string($id) && preg_match('/^user[_:-]?(\d+)$/i', trim($id), $matches)) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->sort()
            ->values();
    }
};
