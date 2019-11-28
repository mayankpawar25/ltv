<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesmanNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesman_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salesman_id')->nullable();
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->tinyInteger('is_viewed')->nullable()->default('0')->comment('0=Unread, 1 = Read');
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
        Schema::dropIfExists('salesman_notifications');
    }
}
