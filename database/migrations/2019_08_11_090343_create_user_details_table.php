<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('gender')->nullable();
            $table->smallInteger('home_address')->nullable()->comment('From User Address Table');
            $table->smallInteger('area_id')->nullable(); //Driver's main area ID
            $table->date('dob')->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->date('joined_date')->nullable();
            $table->longText('documents')->nullable();
            $table->string('referral_id')->nullable();
            $table->string('referred_by')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('user_details');
    }
}
