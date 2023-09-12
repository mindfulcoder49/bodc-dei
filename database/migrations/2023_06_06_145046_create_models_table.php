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
        Schema::create('ml_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ml_model_name')->unique();
            $table->string('ml_model_type');
            $table->date('ml_model_date');
        });
    
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_models');
    }
};
