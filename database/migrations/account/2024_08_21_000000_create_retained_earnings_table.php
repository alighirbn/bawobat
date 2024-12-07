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
        // Migration for retained earnings
        Schema::create('retained_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id'); // Foreign key to periods table
            $table->foreign('period_id')->references('id')->on('periods');
            $table->decimal('beginning_balance', 15, 0); // Beginning retained earnings balance for the period
            $table->decimal('net_income', 15, 0); // Net income for the period (from profit and loss statement)
            $table->decimal('dividends', 15, 0)->nullable(); // Dividends for the period
            $table->decimal('ending_balance', 15, 0); // Ending retained earnings balance for the period
            $table->foreignId('user_id_create')->nullable()->constrained('users')->nullOnDelete(); // Creator user
            $table->foreignId('user_id_update')->nullable()->constrained('users')->nullOnDelete(); // Updater user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retained_earnings');
    }
};
