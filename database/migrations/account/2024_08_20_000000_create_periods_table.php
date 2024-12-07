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
        // Migration for Chart of Accounts
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('url_address')->unique(); // Unique URL identifier
            $table->string('name'); // Period name (e.g., '2024-Q1', '2024-Jan')
            $table->date('start_date'); // Start date of the period
            $table->date('end_date'); // End date of the period
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('user_id_create')->nullable()->constrained('users')->nullOnDelete(); // Creator user
            $table->foreignId('user_id_update')->nullable()->constrained('users')->nullOnDelete(); // Updater user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
