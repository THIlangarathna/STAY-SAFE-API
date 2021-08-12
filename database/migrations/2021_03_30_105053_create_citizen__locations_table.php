<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizenLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen__locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('citizen_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();

            $table->index('citizen_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizen__locations');
    }
}
