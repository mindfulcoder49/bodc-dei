<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Building Permits Table
        Schema::create('building_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permitnumber')->nullable();
            $table->string('worktype')->nullable();
            $table->string('permittypedescr')->nullable();
            $table->text('description')->nullable();
            $table->text('comments')->nullable();
            $table->string('applicant')->nullable();
            $table->string('declared_valuation')->nullable();
            $table->string('total_fees')->nullable();
            $table->timestamp('issued_date')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->string('status')->nullable();
            $table->string('occupancytype')->nullable();
            $table->integer('sq_feet')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('property_id')->nullable();
            $table->string('parcel_id')->nullable();
            $table->decimal('gpsy', 15, 8)->nullable();
            $table->decimal('gpsx', 15, 8)->nullable();
            $table->decimal('y_latitude', 15, 13)->nullable();
            $table->decimal('x_longitude', 15, 13)->nullable();
            $table->timestamps();
        });

        // Construction Off Hours Table
        Schema::create('construction_off_hours', function (Blueprint $table) {
            $table->id();
            $table->string('app_no')->nullable();
            $table->timestamp('start_datetime')->nullable();
            $table->timestamp('stop_datetime')->nullable();
            $table->string('address')->nullable();
            $table->string('ward')->nullable();
            $table->timestamps();
        });

        // Trash Schedules by Address Table
        Schema::create('trash_schedules_by_address', function (Blueprint $table) {
            $table->id();
            $table->string('sam_address_id')->nullable();
            $table->string('full_address')->nullable();
            $table->string('mailing_neighborhood')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('x_coord', 15, 13)->nullable();
            $table->decimal('y_coord', 15, 13)->nullable();
            $table->boolean('recollect')->default(0);
            $table->string('trashday')->nullable();
            $table->string('pwd_district')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_permits');
        Schema::dropIfExists('construction_off_hours');
        Schema::dropIfExists('trash_schedules_by_address');
    }
}
