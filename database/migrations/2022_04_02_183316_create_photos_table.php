<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable(); 
            $table->string('url');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
