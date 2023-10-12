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
            $table->unsignedBigInteger('three_one_one_case_id');  // Foreign key to three_one_one_cases
            $table->bigInteger('case_enquiry_id');
            $table->unsignedBigInteger('ml_model_id');  // Foreign key to ml_models
            $table->string('ml_model_name');  // Descriptive string for the model's name
            $table->text('prediction');
            $table->date('prediction_date');

            $table->foreign('three_one_one_case_id')->references('id')->on('three_one_one_cases')->onDelete('cascade'); 
            $table->foreign('ml_model_id')->references('id')->on('ml_models')->onDelete('cascade');

            //make the combination of case_enquiry_id and ml_model_name unique
            $table->unique(['case_enquiry_id', 'ml_model_name']);
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
