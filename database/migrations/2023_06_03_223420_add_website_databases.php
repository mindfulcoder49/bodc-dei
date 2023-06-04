
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
        Schema::create('site_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('creation_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->text('title')->nullable();
            $table->text('source')->nullable();
        });

        Schema::create('site_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('creation_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->text('title')->nullable();
            $table->text('source')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('site_pages');
        Schema::dropIfExists('site_projects');
    }
};

