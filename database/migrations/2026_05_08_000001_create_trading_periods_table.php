<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trading_periods')) {
            Schema::create('trading_periods', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('year');
                $table->string('name', 120);
                $table->date('offer_starts_at');
                $table->date('offer_ends_at');
                $table->date('processing_starts_at');
                $table->date('processing_ends_at');
                $table->date('private_starts_at');
                $table->date('private_ends_at');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['year', 'is_active']);
            });
        }

        $year = (int) now()->year;

        DB::table('trading_periods')->updateOrInsert(
            ['year' => $year, 'name' => 'الفترة الأولى'],
            [
                'offer_starts_at' => "{$year}-01-01",
                'offer_ends_at' => "{$year}-01-10",
                'processing_starts_at' => "{$year}-01-11",
                'processing_ends_at' => "{$year}-01-20",
                'private_starts_at' => "{$year}-01-21",
                'private_ends_at' => "{$year}-01-30",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('trading_periods')->updateOrInsert(
            ['year' => $year, 'name' => 'الفترة الثانية'],
            [
                'offer_starts_at' => "{$year}-07-01",
                'offer_ends_at' => "{$year}-07-10",
                'processing_starts_at' => "{$year}-07-11",
                'processing_ends_at' => "{$year}-07-20",
                'private_starts_at' => "{$year}-07-21",
                'private_ends_at' => "{$year}-07-30",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_periods');
    }
};
