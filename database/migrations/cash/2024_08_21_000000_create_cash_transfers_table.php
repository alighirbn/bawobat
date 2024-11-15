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
        Schema::create('cash_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_account_id')->constrained('cash_accounts')->onDelete('cascade'); // Source account
            $table->foreignId('to_account_id')->constrained('cash_accounts')->onDelete('cascade'); // Target account
            $table->decimal('amount', 15, 2); // Transfer amount
            $table->text('description')->nullable(); // Optional description
            $table->date('transaction_date'); // Transfer date
            $table->timestamps();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');

            $table->string('url_address', '60')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transfers');
    }
};
