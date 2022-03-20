<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('domain_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('fevicon')->nullable();
            $table->string('description')->nullable();
            $table->integer('ratings')->nullable();
            $table->string('picture')->nullable();
            $table->integer('start_price')->nullable();
            $table->string('speciality')->nullable();
            $table->string('project_meta')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
