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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('case_enquiry_id');
            $table->foreignId('ml_model_id')->constrained('ml_models');
            $table->float('prediction');
            $table->date('prediction_date');

            $table->foreign('case_enquiry_id')->references('case_enquiry_id')->on('three_one_one_cases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
