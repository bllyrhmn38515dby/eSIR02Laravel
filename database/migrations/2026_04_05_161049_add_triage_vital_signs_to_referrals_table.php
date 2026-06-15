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
            $table->string('blood_pressure')->nullable()->after('diagnosis');
            $table->integer('heart_rate')->nullable()->after('blood_pressure');
            $table->integer('respiratory_rate')->nullable()->after('heart_rate');
            $table->float('temperature')->nullable()->after('respiratory_rate');
            $table->integer('gcs_score')->nullable()->after('temperature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            //
        });
    }
};
