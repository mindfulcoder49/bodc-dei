<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('three_one_one_cases', function (Blueprint $table) {
            $table->id(); // New auto-incrementing ID column
            $table->bigInteger('case_enquiry_id')->unique(); // Removed primary()
            $table->dateTime('open_dt')->nullable();
            $table->text('sla_target_dt')->nullable();
            $table->dateTime('closed_dt')->nullable();
            $table->text('on_time')->nullable();
            $table->text('case_status')->nullable();
            $table->text('closure_reason')->nullable();
            $table->text('case_title')->nullable();
            $table->text('subject')->nullable();
            $table->text('reason')->nullable();
            $table->text('type')->nullable();
            $table->text('queue')->nullable();
            $table->text('department')->nullable();
            $table->text('submitted_photo')->nullable();
            $table->text('closed_photo')->nullable();
            $table->text('location')->nullable();
            $table->text('fire_district')->nullable();
            $table->text('pwd_district')->nullable();
            $table->text('city_council_district')->nullable();
            $table->text('police_district')->nullable();
            $table->text('neighborhood')->nullable();
            $table->text('neighborhood_services_district')->nullable();
            $table->text('ward')->nullable();
            $table->text('precinct')->nullable();
            $table->text('location_street_name')->nullable();
            $table->double('location_zipcode')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('source')->nullable();
            $table->text('checksum')->nullable();
            $table->text('ward_number')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('three_one_one_cases');
    }
};