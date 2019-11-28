<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',225)->nullable();
            $table->string('iso_code',225)->nullable();
            $table->string('phone_code',225)->nullable();
            $table->tinyInteger('status')->default('1')->comment('0=Inactive,1=Active'); 
            $table->tinyInteger('approved')->default('0')->comment('0=Processing,1=On Hold,2=Accepted,3=Rejected');
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
        Schema::dropIfExists('countries');
    }
}
