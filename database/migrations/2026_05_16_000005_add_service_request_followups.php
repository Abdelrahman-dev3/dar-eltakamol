<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE bookings MODIFY status VARCHAR(30) NOT NULL DEFAULT 'received'");
            }

            DB::table('bookings')->where('status', 'pending')->update(['status' => 'received']);
            DB::table('bookings')->where('status', 'confirmed')->update(['status' => 'in_progress']);
        }

        Schema::create('booking_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_type', 30)->default('contributor');
            $table->text('message');
            $table->timestamps();

            $table->index(['booking_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_messages');

        if (Schema::hasTable('bookings') && Schema::getConnection()->getDriverName() === 'mysql') {
            DB::table('bookings')->where('status', 'received')->update(['status' => 'pending']);
            DB::table('bookings')->where('status', 'in_progress')->update(['status' => 'confirmed']);
            DB::table('bookings')->whereNotIn('status', ['pending', 'confirmed'])->update(['status' => 'pending']);

            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed') NOT NULL DEFAULT 'pending'");
        }
    }
};
