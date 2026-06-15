<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->foreignId('bed_capacity_id')->nullable()->constrained('bed_capacities')->nullOnDelete();
            $table->integer('response_time_minutes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['bed_capacity_id']);
            $table->dropColumn(['bed_capacity_id', 'response_time_minutes']);
        });
    }
};
