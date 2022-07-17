<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('city_id')->unsigned()->nullable();
            $table->string('description');
            $table->json('rules');
            $table->string('image_url');
            $table->string('bg_image_url');
            $table->json('price');
            $table->integer('rating')->nullable();
            $table->integer('visitors_count')->nullable();
            $table->json('social_media');
            $table->json('contact_details');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
