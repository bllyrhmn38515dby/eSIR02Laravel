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
        Schema::create('ambulances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faskes_id')->constrained('faskes')->onDelete('cascade');
            $table->string('police_number')->unique();
            $table->string('vehicle_type')->default('Standard');
            $table->enum('status', ['standby', 'on_trip', 'maintenance'])->default('standby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulances');
    }
};
