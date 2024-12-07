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
        // Migration for opening balances
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->string('url_address')->unique(); // Unique URL identifier
            $table->unsignedBigInteger('account_id'); // Foreign key to accounts table
            $table->unsignedBigInteger('period_id'); // Foreign key to periods table
            $table->decimal('balance', 15, 0); // Opening balance value
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('period_id')->references('id')->on('periods');

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
        Schema::dropIfExists('opening_balances');
    }
};
