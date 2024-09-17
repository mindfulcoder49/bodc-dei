<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrimeDataTable extends Migration
{
    public function up()
    {
        Schema::create('crime_data', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->integer('offense_code');
            $table->string('offense_code_group');
            $table->string('offense_description');
            $table->string('district')->nullable();
            $table->string('reporting_area')->nullable();
            $table->boolean('shooting')->nullable();
            $table->dateTime('occurred_on_date');
            $table->integer('year');
            $table->integer('month');
            $table->string('day_of_week');
            $table->integer('hour');
            $table->string('ucr_part')->nullable();
            $table->string('street')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->string('location')->nullable();
            $table->string('offense_category')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crime_data');
    }
}

