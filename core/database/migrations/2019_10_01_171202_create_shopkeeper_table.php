<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopkeeperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopkeepers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('shopname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('password')->nullable();
            $table->string('mobile')->nullable();
            $table->text('images')->nullable();
            $table->text('documents')->nullable();
            $table->text('address')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('zipcode_id')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('employer_contactno')->nullable();
            $table->string('tags')->nullable();
            $table->tinyInteger('status')->default('0')->nullable()->comment('0 = Inactive ,1 = Active ');
            $table->string('user_role')->nullable();
            $table->text('qr_code')->nullable();
            $table->integer('email_ver_code')->nullable();
            $table->integer('sms_ver_code')->nullable();
            $table->tinyInteger('email_sent')->nullable()->comment('0 = Not sent , 1 = Sent');
            $table->tinyInteger('sms_sent')->nullable()->comment('0 = Not sent , 1 = Sent');
            $table->tinyInteger('email_verified')->nullable()->comment('0 = unverified , 1 = Verified');
            $table->tinyInteger('sms_verified')->nullable()->comment('0 = unverified , 1 = Verified');
            $table->string('folder')->nullable();
            $table->tinyInteger('is_verified')->nullable()->comment('0 = Under Review , 1 = Verified , 2 = Hold , 3 = Deactivate');
            $table->integer('salesman_id')->nullable();
            $table->text('fcm_id')->nullable();
            $table->text('token')->nullable();
            $table->integer('usergroup_id')->nullable()->default('0');
            $table->string('admin_verify',300)->nullable()->default('0');
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
        Schema::dropIfExists('shopkeepers');
    }
}
