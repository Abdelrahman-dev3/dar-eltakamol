<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('independent_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contributor_id')->constrained('contributors')->cascadeOnDelete();
            $table->decimal('count', 15, 2);
            $table->decimal('amount_per_share', 15, 2);
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamp('requested_at')->nullable();
            $table->timestamps();

            $table->index(['contributor_id', 'status']);
            $table->index('requested_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('independent_purchase_orders');
    }
};
