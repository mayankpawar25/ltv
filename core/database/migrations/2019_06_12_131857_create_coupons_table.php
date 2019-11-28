<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code',255)->nullable();
            $table->string('coupon_type',30)->nullable();
            $table->decimal('coupon_amount',11,2)->nullable();
            $table->string('coupon_min_amount',11,2)->nullable();
            $table->string('valid_till',225)->nullable();
            $table->string('show_validity',225)->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
