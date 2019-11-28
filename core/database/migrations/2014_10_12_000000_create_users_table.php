<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('password')->nullable();

            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_email')->nullable();
            $table->boolean('shipping_is_same_as_billing')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip_code')->nullable();
            $table->unsignedInteger('shipping_country_id')->nullable();
            
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();

            $table->string('billing_address')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('staff_user_id')->nullable();
            $table->string('assigned_to')->nullable();

            $table->string('status')->nullable();
            $table->string('email_verified')->nullable();
            $table->string('sms_verified')->nullable();
            $table->string('email_ver_code')->nullable();
            $table->string('sms_ver_code')->nullable();
            $table->string('email_sent')->nullable();
            $table->string('sms_sent')->nullable();
            $table->string('vsent')->nullable();

            $table->string('default_language', 10)->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->string('stripe_id')->nullable();            
            $table->boolean('inactive')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('fb_provider')->nullable();
            $table->string('fb_provider_id')->nullable();
            $table->string('remember_token')->nullable();
            $table->unsignedInteger('created_by')->nullable();  
            $table->text('fcm_id')->nullable(); 
            $table->text('token')->nullable();         
            $table->timestamps();
            $table->softDeletes();


            $table->index('number');
            $table->index('name');
            $table->index('phone'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}