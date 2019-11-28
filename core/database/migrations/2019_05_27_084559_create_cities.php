<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('city_code');
            $table->integer('state_id');
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
        Schema::dropIfExists('cities');
    }
}
