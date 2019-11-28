<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('alternate_no')->nullable();
            $table->date('collection_date')->nullable();
            $table->date('new_date')->nullable();
            $table->decimal('amount',50,2)->nullable();
            $table->decimal('collected_amount',50,2)->nullable();
            $table->decimal('balance_amount',50,2)->nullable();
            $table->tinyInteger('status')->default('0')->comment('0=Closed,1=Open,2=Closed by Salesman');
            $table->integer('staff_user_id')->nullable();
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
        Schema::dropIfExists('payment_collections');
    }
}
