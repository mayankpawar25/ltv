<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardFeildValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('job_card_feild_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_card_template_id')->nullable();
            $table->string('label')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('job_card_feild_values');
    }
}
