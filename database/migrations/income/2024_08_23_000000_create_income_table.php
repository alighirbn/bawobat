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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('url_address')->unique(); // Unique URL identifier

            $table->foreignId('cost_center_id')->nullable()->constrained('costcenters')->nullOnDelete(); // Foreign key for Cost Center

            $table->foreignId('debit_account_id')->nullable()->constrained('accounts')->nullOnDelete(); // Foreign key for Debit Account
            $table->foreignId('credit_account_id')->nullable()->constrained('accounts')->nullOnDelete(); // Foreign key for Credit Account

            $table->date('date'); // Date of the income
            $table->decimal('amount', 15, 0); // Amount with precision
            $table->text('description')->nullable(); // Optional description

            $table->boolean('approved')->default(false); // Approval status, default is false

            $table->foreignId('user_id_create')->nullable()->constrained('users')->nullOnDelete(); // Creator user
            $table->foreignId('user_id_update')->nullable()->constrained('users')->nullOnDelete(); // Updater user

            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
