<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zipcode_name');
            $table->string('area_name');
            $table->integer('city_id');
            $table->integer('state_id');
            $table->integer('country_id');
            $table->tinyInteger('status')->default('1')->comment('0=Inactive,1=Active');   
            $table->tinyInteger('approved')->default('1')->comment('0=Processing,1=On Hold,2=Accepted,3=Rejected');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();   
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zipcodes');
    }
}
