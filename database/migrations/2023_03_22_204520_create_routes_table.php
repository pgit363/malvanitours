<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_place_id')->unsigned()->nullable();
            $table->integer('destination_place_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->json('description')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamp('departure_time')->nullable();
            $table->timestamp('arrival_time')->nullable();
            $table->timestamp('total_time')->nullable();
            $table->timestamp('delayed_time')->nullable();
            $table->timestamps();

            $table->foreign('source_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('destination_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
