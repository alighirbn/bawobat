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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('url_address')->unique(); // Unique URL identifier
            $table->foreignId('user_id_create')->nullable()->constrained('users')->nullOnDelete(); // Creator user
            $table->foreignId('user_id_update')->nullable()->constrained('users')->nullOnDelete(); // Updater user

            $table->integer('code')->unique(); // Account code (e.g., 101 for "Capital")
            $table->string('name');           // Account name (e.g., 'Capital')
            $table->enum('type', [
                'Asset',
                'Liability',
                'Equity',
                'Income',
                'Expense'
            ]); // Account type (Asset, Liability, Equity, Expense, Income)
            $table->enum('category', [
                'Current',
                'Non-Current',
                'Operating',
                'Non-Operating'
            ]); // Account category (Current, Non-Current, Operating, Non-Operating)
            $table->unsignedBigInteger('parent_id')->nullable(); // Add the parent_id column
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->integer('class');         // Class (1, 2, 3, etc. from PCG)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
