<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('BuildingPermits', function (Blueprint $table) {
            $table->id();
            $table->string('permitnumber');
            $table->string('worktype');
            $table->string('permittypedescr');
            $table->string('description');
            $table->string('comments');
            $table->string('applicant');
            $table->string('declared_valuation');
            $table->string('total_fees');
            $table->string('issued_date');
            $table->string('expiration_date');
            $table->string('status');
            $table->string('occupancytype');
            $table->decimal('sq_feet');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('property_id');
            $table->decimal('parcel_id');
            $table->decimal('gpsy');
            $table->decimal('gpsx');
            $table->decimal('y_latitude');
            $table->decimal('x_longitude');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('BuildingPermits');
    }
};
