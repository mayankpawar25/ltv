<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_code')->nullable();            
            $table->string('code')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedInteger('gender_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('alternate_no')->nullable();
            $table->text('address')->nullable();
            $table->string('job_title')->nullable();
            $table->decimal('salary', 10,2)->nullable();
            $table->string('salary_term')->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('reporting_boss')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('skype')->nullable();
            $table->string('email_signature')->nullable();
            $table->string('photo')->nullable();
            $table->string('layout_direction')->nullable();

            $table->string('bank_account_no')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('adharcard_no')->nullable();
            $table->string('pancard_no')->nullable();
            $table->string('other_details')->nullable();

            $table->boolean('is_administrator')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->boolean('inactive')->nullable();
            $table->text('task_timer')->nullable();
            $table->text('fcm_id')->nullable();
            $table->text('token')->nullable();

            $table->integer('level')->default('0')->comment('0=none,1=level 1, 2=level 2');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_users');
    }
}
