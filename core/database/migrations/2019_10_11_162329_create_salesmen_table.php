<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesmen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('emp_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('password')->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('status')->default('0')->nullable()->comment('0 = Inactive ,1 = Active ');
            $table->text('fcm_id')->nullable();
            $table->text('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesmen');
    }
}
