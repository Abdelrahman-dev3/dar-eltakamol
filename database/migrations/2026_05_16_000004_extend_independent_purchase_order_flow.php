<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('independent_purchase_orders', function (Blueprint $table): void {
            if (!Schema::hasColumn('independent_purchase_orders', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('requested_at');
            }

            if (!Schema::hasColumn('independent_purchase_orders', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('published_at');
            }
        });

        Schema::table('sell_shares', function (Blueprint $table): void {
            if (!Schema::hasColumn('sell_shares', 'independent_purchase_order_id')) {
                $table->foreignId('independent_purchase_order_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('independent_purchase_orders')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('sell_shares', 'independent_offer_status')) {
                $table->string('independent_offer_status', 30)->nullable()->after('independent_purchase_order_id');
            }

            if (!Schema::hasColumn('sell_shares', 'accepted_count')) {
                $table->decimal('accepted_count', 15, 2)->default(0)->after('independent_offer_status');
            }

            if (!Schema::hasColumn('sell_shares', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('accepted_count');
            }

            $table->index(['independent_purchase_order_id', 'independent_offer_status'], 'sell_shares_independent_offer_idx');
        });
    }

    public function down(): void
    {
        Schema::table('sell_shares', function (Blueprint $table): void {
            if (Schema::hasColumn('sell_shares', 'independent_purchase_order_id')) {
                $table->dropForeign(['independent_purchase_order_id']);
            }

            $table->dropIndex('sell_shares_independent_offer_idx');

            $columns = array_filter([
                Schema::hasColumn('sell_shares', 'responded_at') ? 'responded_at' : null,
                Schema::hasColumn('sell_shares', 'accepted_count') ? 'accepted_count' : null,
                Schema::hasColumn('sell_shares', 'independent_offer_status') ? 'independent_offer_status' : null,
                Schema::hasColumn('sell_shares', 'independent_purchase_order_id') ? 'independent_purchase_order_id' : null,
            ]);

            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('independent_purchase_orders', function (Blueprint $table): void {
            $columns = array_filter([
                Schema::hasColumn('independent_purchase_orders', 'closed_at') ? 'closed_at' : null,
                Schema::hasColumn('independent_purchase_orders', 'published_at') ? 'published_at' : null,
            ]);

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
