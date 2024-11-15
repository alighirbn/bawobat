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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('budget', 15, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'completed', 'canceled'])->default('pending');
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
        Schema::dropIfExists('projects');
    }
};
