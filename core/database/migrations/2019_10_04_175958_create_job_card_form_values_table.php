<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardFormValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('job_card_form_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_card_template_id')->nullable();
            $table->integer('job_card_form_id')->nullable();
            $table->integer('job_card_feild_value_id')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('job_card_form_values');
    }
}
