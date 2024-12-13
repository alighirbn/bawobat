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
            $table->string('name');
            $table->date('date');
            $table->foreignId('period_id')->constrained();
            $table->string('url_address');
            $table->foreignId('user_id_create')->constrained('users');
            $table->foreignId('user_id_update')->nullable()->constrained('users');
            $table->timestamps();
        });

        // If you're using a pivot table for accounts
        Schema::create('opening_balance_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opening_balance_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained();
            $table->decimal('amount', 15, 0);
            $table->enum('debit_credit', ['debit', 'credit']); // Debit or Credit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_balance_accounts');
        Schema::dropIfExists('opening_balances');
    }
};
