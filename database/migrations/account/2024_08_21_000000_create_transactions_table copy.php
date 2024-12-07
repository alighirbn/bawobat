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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('url_address')->unique(); // Unique URL identifier
            $table->foreignId('user_id_create')->nullable()->constrained('users')->nullOnDelete(); // Creator user
            $table->foreignId('user_id_update')->nullable()->constrained('users')->nullOnDelete(); // Updater user
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->date('date');
            $table->text('description');
            $table->unsignedBigInteger('transactionable_id')->nullable();
            $table->string('transactionable_type')->nullable();

            $table->timestamps();
        });

        Schema::create('transaction_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 0); // Debit or Credit amount
            $table->enum('debit_credit', ['debit', 'credit']); // Debit or Credit
            $table->foreignId('cost_center_id')->nullable()->constrained('costcenters')->onDelete('set null'); // Link to a specific cost center
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_account');
        Schema::dropIfExists('transactions');
    }
};
