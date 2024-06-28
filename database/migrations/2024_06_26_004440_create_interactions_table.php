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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('prompt');
            $table->text('completion');
            $table->unsignedBigInteger('prompt_tokens');
            $table->unsignedBigInteger('completion_tokens');
            $table->decimal('prompt_token_price', 10, 5);   // Supports decimal prices
            $table->decimal('completion_token_price', 10, 5); // Supports decimal prices
            $table->decimal('total_cost', 10, 5); // Decimal to support fractional costs
            $table->string('model_name'); // To store the name of the model used
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
