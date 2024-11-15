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
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // New field
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('income_type_id')->constrained()->onDelete('restrict');
            $table->string('source');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('incomes');
    }
};
