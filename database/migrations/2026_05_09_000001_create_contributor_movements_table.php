<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contributor_movements')) {
            return;
        }

        Schema::create('contributor_movements', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedTinyInteger('movement_type');
            $table->foreignId('from_contributor_id')->nullable()->constrained('contributors')->nullOnDelete();
            $table->foreignId('to_contributor_id')->nullable()->constrained('contributors')->nullOnDelete();
            $table->decimal('shares_count', 15, 2)->default(0);
            $table->decimal('amount_per_share', 15, 2)->default(0);
            $table->decimal('from_balance_before', 15, 2)->nullable();
            $table->decimal('from_balance_after', 15, 2)->nullable();
            $table->decimal('to_balance_before', 15, 2)->nullable();
            $table->decimal('to_balance_after', 15, 2)->nullable();
            $table->text('description');
            $table->foreignId('shares_trans_id')->nullable()->constrained('shares_trans')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['movement_type', 'date'], 'cm_type_date_idx');
            $table->index(['from_contributor_id', 'to_contributor_id'], 'cm_from_to_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributor_movements');
    }
};
