<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sell_share_settlements')) {
            Schema::create('sell_share_settlements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sell_share_id')->constrained('sell_shares')->cascadeOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status', 30)->default('draft');
                $table->string('method', 30)->default('buyers');
                $table->decimal('offered_count', 15, 2)->default(0);
                $table->decimal('allocated_count', 15, 2)->default(0);
                $table->decimal('transferred_count', 15, 2)->default(0);
                $table->timestamp('posted_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique('sell_share_id');
            });
        }

        if (!Schema::hasTable('sell_share_allocations')) {
            Schema::create('sell_share_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('settlement_id')->constrained('sell_share_settlements')->cascadeOnDelete();
                $table->foreignId('sell_share_id')->constrained('sell_shares')->cascadeOnDelete();
                $table->foreignId('shares_po_id')->nullable()->constrained('shares_poes')->nullOnDelete();
                $table->foreignId('seller_id')->constrained('contributors')->cascadeOnDelete();
                $table->foreignId('buyer_id')->nullable()->constrained('contributors')->nullOnDelete();
                $table->string('allocation_type', 30)->default('buyer');
                $table->decimal('shares_count', 15, 2)->default(0);
                $table->decimal('amount_per_share', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('transferred_count', 15, 2)->default(0);
                $table->string('status', 30)->default('pending');
                $table->timestamp('posted_at')->nullable();
                $table->timestamps();

                $table->index(['sell_share_id', 'status']);
                $table->index(['shares_po_id', 'status']);
            });
        }

        if (!Schema::hasColumn('shares_poes', 'transferred_count')) {
            Schema::table('shares_poes', function (Blueprint $table) {
                $table->decimal('transferred_count', 15, 2)->default(0)->after('po_status');
                $table->timestamp('defaulted_at')->nullable()->after('transferred_count');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shares_poes', 'transferred_count')) {
            Schema::table('shares_poes', function (Blueprint $table) {
                $table->dropColumn(['transferred_count', 'defaulted_at']);
            });
        }

        Schema::dropIfExists('sell_share_allocations');
        Schema::dropIfExists('sell_share_settlements');
    }
};
