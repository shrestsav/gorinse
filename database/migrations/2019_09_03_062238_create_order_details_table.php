<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->unsigned()->unique();
            $table->integer('PAB')->unsigned()->nullable()->comment('Pick Assigned By');
            $table->integer('DAB')->unsigned()->nullable()->comment('Drop Assigned By');
            $table->dateTime('PAT')->nullable()->comment('Pick Assigned Time / Accepted Time');
            $table->dateTime('DAT')->nullable()->comment('Drop Assigned Time');
            $table->dateTime('PFC')->nullable()->comment('Picked From Customer');
            $table->dateTime('DAO')->nullable()->comment('Dropped At Office');
            $table->dateTime('PFO')->nullable()->comment('Picked From Office');
            $table->dateTime('DTC')->nullable()->comment('Delivered To Customer');
            $table->smallInteger('payment_type')->nullable()->comment('1:Cash On Delivery, 2:Card, 3: Paypal');
            $table->string('invoice_id')->nullable()->comment('Invoice ID from Paypal or Payfort');
            $table->dateTime('PT')->nullable()->comment('Payment Time');
            $table->text('PDR')->nullable()->comment('Pickup Driver Remark');

            $table->foreign('order_id')->references('id')->on('orders')
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
        Schema::dropIfExists('order_details');
    }
}
