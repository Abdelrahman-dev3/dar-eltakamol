<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Contributor;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ParticipantAudienceResolver
{
    public const SCOPE_MANUAL = 'manual';
    public const SCOPE_ALL_USERS = 'all_users';
    public const SCOPE_ALL_CONTRIBUTORS = 'all_contributors';
    public const SCOPE_BOARD_MEMBERS = 'board_members';
    public const SCOPE_COMMITTEE = 'committee';
    public const SCOPE_COMPANY = 'company';
    public const SCOPE_DEPARTMENT = 'department';

    public function scopeOptions(): array
    {
        return [
            self::SCOPE_MANUAL => 'اختيار مستخدمين محددين',
            self::SCOPE_ALL_USERS => 'جميع المستخدمين',
            self::SCOPE_ALL_CONTRIBUTORS => 'جميع المساهمين',
            self::SCOPE_BOARD_MEMBERS => 'أعضاء مجلس الإدارة',
            self::SCOPE_COMMITTEE => 'أعضاء لجنة محددة',
            self::SCOPE_COMPANY => 'حسب الشركة أو العضوية الرئيسية',
            self::SCOPE_DEPARTMENT => 'حسب الإدارة أو التصنيف الفرعي',
        ];
    }

    public function committeeOptions(): array
    {
        return Contributor::committeeMembershipOptions();
    }

    public function resolve(
        ?string $scope,
        array $manualUserIds = [],
        ?int $categoryId = null,
        ?string $committee = null
    ): array {
        $scope = $scope ?: self::SCOPE_MANUAL;

        $userIds = match ($scope) {
            self::SCOPE_ALL_USERS => $this->normalizeIds(User::query()->pluck('id')->all())->all(),
            self::SCOPE_ALL_CONTRIBUTORS => $this->normalizeIds($this->contributorsQuery()->pluck('user_id')->all())->all(),
            self::SCOPE_BOARD_MEMBERS => $this->normalizeIds($this->contributorsQuery()->where('is_board_member', true)->pluck('user_id')->all())->all(),
            self::SCOPE_COMMITTEE => $this->committeeUserIds($committee),
            self::SCOPE_COMPANY => $this->categoryUserIds($categoryId, true),
            self::SCOPE_DEPARTMENT => $this->categoryUserIds($categoryId, false),
            default => $this->normalizeIds($manualUserIds)->all(),
        };

        return $this->existingUserIds($userIds);
    }

    private function contributorsQuery()
    {
        return Contributor::query()->whereNotNull('user_id');
    }

    private function committeeUserIds(?string $committee): array
    {
        if (!$committee || !in_array($committee, $this->committeeOptions(), true)) {
            throw ValidationException::withMessages([
                'audience_committee' => 'يرجى اختيار لجنة صحيحة.',
            ]);
        }

        return $this->contributorsQuery()
            ->get(['user_id', 'committee_memberships'])
            ->filter(fn (Contributor $contributor) => in_array($committee, $contributor->committee_memberships ?? [], true))
            ->pluck('user_id')
            ->pipe(fn ($ids) => $this->normalizeIds($ids->all()))
            ->all();
    }

    private function categoryUserIds(?int $categoryId, bool $company): array
    {
        $category = Category::query()
            ->when($company, fn ($query) => $query->companies(), fn ($query) => $query->departments())
            ->find($categoryId);

        if (!$category) {
            throw ValidationException::withMessages([
                'audience_category_id' => $company
                    ? 'يرجى اختيار شركة أو عضوية رئيسية صحيحة.'
                    : 'يرجى اختيار إدارة أو تصنيف فرعي صحيح.',
            ]);
        }

        return $this->normalizeIds(
            $category->users()->pluck('users.id')
                ->merge($category->contributors()->whereNotNull('contributors.user_id')->pluck('contributors.user_id'))
                ->all()
        )->all();
    }

    private function normalizeIds(array $ids): Collection
    {
        return collect($ids)
            ->filter()
            ->map(function ($id) {
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
            ->values();
    }

    private function existingUserIds(array $ids): array
    {
        $normalizedIds = $this->normalizeIds($ids);

        if ($normalizedIds->isEmpty()) {
            return [];
        }

        $existingIds = User::query()
            ->whereIn('id', $normalizedIds->all())
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return $normalizedIds
            ->intersect($existingIds)
            ->values()
            ->all();
    }
}
