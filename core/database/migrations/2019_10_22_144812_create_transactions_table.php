<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('client_type_id')->comment('1- Shopkeeper, 2- Leads, 3- Customer')->nullable();
            $table->text('details')->nullable();
            $table->decimal('amount',50,2)->nullable();
            $table->text('trx_id')->nullable();
            $table->integer('staff_user_id')->nullable();
            $table->decimal('credit',50,2)->nullable();
            $table->decimal('debit',50,2)->nullable();
            $table->decimal('after_balance',50,2)->nullable();
            $table->string('payment_mode')->comment('1 - Cash, 2- Cheque, 3- NEFT, 4- Other')->nullable();
            $table->string('staff_user_remark')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
