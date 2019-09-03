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
            $table->dateTime('PAT')->nullable()->comment('Pick Assigned Time / Accepted Time');
            $table->dateTime('DAT')->nullable()->comment('Drop Assigned Time');
            $table->dateTime('PFC')->nullable()->comment('Picked From Customer');
            $table->dateTime('DAO')->nullable()->comment('Dropped At Office');
            $table->dateTime('PFO')->nullable()->comment('Picked From Office');
            $table->dateTime('DTC')->nullable()->comment('Delivered To Customer');
            $table->dateTime('PT')->nullable()->comment('Payment Time');
            $table->text('PDR')->nullable()->comment('Pickup Driver Remark');
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
