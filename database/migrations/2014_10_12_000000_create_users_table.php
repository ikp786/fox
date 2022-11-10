<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 20);
            $table->string('last_name', 20)->nullable();
            $table->string('email', 70)->nullable();
            $table->string('mobile',15);
            $table->integer('otp')->nullable();
            $table->integer('otp_status')->default(0);
            $table->string('profile_pic', 50)->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->Integer('role');
            $table->string('password', 255)->nullable();
            $table->string('device_token', 255)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
