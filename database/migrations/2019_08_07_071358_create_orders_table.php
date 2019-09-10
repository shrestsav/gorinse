<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->unsigned();
            $table->smallInteger('type')->comment('1:Normal, 2:Urgent');
            $table->integer('driver_id')->unsigned()->nullable()->comment('Pick Driver ID');
            $table->integer('drop_driver_id')->unsigned()->nullable();
            $table->smallInteger('pick_location')->comment('From User Address Table');
            $table->date('pick_date');
            $table->string('pick_timerange');
            $table->smallInteger('drop_location')->comment('From User Address Table');
            $table->date('drop_date')->nullable();
            $table->string('drop_timerange')->nullable();
            $table->smallInteger('status')->default(0)->comment('See Config');
            $table->integer('VAT')->nullable();
            $table->integer('delivery_charge')->nullable();
            $table->string('coupon')->nullable();
            $table->smallInteger('payment')->default(0)->comment('0:Pending, 1:Paid');
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
        Schema::dropIfExists('orders');
    }
}
