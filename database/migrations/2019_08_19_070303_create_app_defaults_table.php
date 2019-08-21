<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppDefaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_defaults', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('VAT')->unsigned();
            $table->integer('delivery_charge')->unsigned();
            $table->integer('OTP_expiry')->unsigned()->comment('Minute');
            $table->longText('order_time');
            $table->longText('driver_notes');
            $table->string('FAQ_link');
            $table->longText('online_chat');
            $table->string('hotline_contact');
            $table->string('company_email');
            $table->string('company_logo');
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
        Schema::dropIfExists('app_defaults');
    }
}