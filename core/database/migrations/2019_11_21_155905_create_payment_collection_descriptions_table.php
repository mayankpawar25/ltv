<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentCollectionDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_collection_descriptions', function(Blueprint $table){
            $table->increments('id');
            $table->text('feedback')->nullable();
            $table->timestamp('calling_date')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('collect_amount',50,2)->default('0.00')->nullable();
            $table->decimal('balance_amount',50,2)->default('0.00')->nullable();
            $table->integer('assigned_to');
            $table->tinyInteger('status')->default('0')->comment('0=open,2=closed by salesman');
            $table->integer('payment_collection_id');
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
        Schema::dropIfExists('payment_collection_descriptions');
    }
}
