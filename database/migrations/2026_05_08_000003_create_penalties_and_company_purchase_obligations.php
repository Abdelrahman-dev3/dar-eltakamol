<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('buyer_penalties')) {
            Schema::create('buyer_penalties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('contributor_id')->nullable()->constrained('contributors')->nullOnDelete();
                $table->foreignId('shares_po_id')->nullable()->constrained('shares_poes')->nullOnDelete();
                $table->string('type', 30)->default('warning');
                $table->text('reason')->nullable();
                $table->date('banned_until')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['contributor_id', 'type']);
                $table->index(['user_id', 'banned_until']);
            });
        }

        if (!Schema::hasTable('company_purchase_obligations')) {
            Schema::create('company_purchase_obligations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sell_share_id')->constrained('sell_shares')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('contributors')->cascadeOnDelete();
                $table->decimal('shares_count', 15, 2)->default(0);
                $table->decimal('amount_per_share', 15, 2)->nullable();
                $table->decimal('total_amount', 15, 2)->nullable();
                $table->unsignedSmallInteger('due_year');
                $table->decimal('annual_percentage', 5, 2)->default(25);
                $table->string('status', 30)->default('scheduled');
                $table->string('payment_kind', 30)->default('cash');
                $table->json('appraisers')->nullable();
                $table->string('selected_appraiser')->nullable();
                $table->decimal('fair_value', 15, 2)->nullable();
                $table->date('valuation_date')->nullable();
                $table->date('due_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['due_year', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_purchase_obligations');
        Schema::dropIfExists('buyer_penalties');
    }
};
