<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('user_type')->default('1')->comment('1 - Dealer (Distributor), 2- Customer (End User)');;
            $table->string('unique_id',225)->nullable();
            $table->string('staff_user_id',225)->nullable();
            $table->string('first_name',225)->nullable();
            $table->string('last_name',225)->nullable();
            $table->string('phone',225)->nullable();
            $table->string('email',225)->nullable();
            $table->string('address',225)->nullable();
            $table->string('country',225)->nullable();
            $table->string('state',225)->nullable();
            $table->string('city',225)->nullable();
            $table->string('zip_code',225)->nullable();
            $table->text('order_notes')->nullable();
            $table->decimal('subtotal',11,2)->nullable();
            $table->decimal('total',11,2)->nullable();
            $table->string('place',225)->nullable();
            $table->decimal('shipping_charge',11,2)->nullable();
            $table->decimal('tax',11,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->string('shipping_method',225)->nullable();
            $table->string('staff_user_remarks',225)->nullable();
            $table->integer('shipping_status')->default('0')->comment('0 - pending, 1- in-process, 2- shipped, 3 - Delivered');
            $table->integer('payment_status')->default('0')->comment('0 - Unpaid, 1- Paid');
            $table->integer('approve')->default('0')->comment('0-pending, 1-approve, -1- reject');
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
        Schema::dropIfExists('orders');
    }
}
