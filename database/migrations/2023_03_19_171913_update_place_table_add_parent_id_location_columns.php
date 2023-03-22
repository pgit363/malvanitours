<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlaceTableAddParentIdLocationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function ($table) {
            $table->integer('parent_id')->unsigned()->nullable()->after('city_id');
            $table->string('latitude')->nullable()->after('contact_details');
            $table->string('longitude')->nullable()->after('latitude');
            $table->json('meta_data')->nullable();

            $table->foreign('parent_id')->references('id')->on('places')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
