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
            $table->integer('driver_id')->unsigned()->nullable()->comment('Pick Driver ID');

            $table->integer('pick_assigned_by')->unsigned()->nullable();
            $table->integer('drop_driver_id')->unsigned()->nullable();
            $table->integer('drop_assigned_by')->unsigned()->nullable();

            $table->smallInteger('type')->comment('1:Normal, 2:Urgent');
            $table->smallInteger('pick_location')->comment('From User Address Table');
            $table->date('pick_date');
            $table->string('pick_timerange');
            $table->smallInteger('drop_location')->comment('From User Address Table');
            $table->date('drop_date')->nullable();
            $table->string('drop_timerange')->nullable();
            $table->smallInteger('payment');
            $table->smallInteger('status')->default(0)->comment('See Config');
            $table->integer('VAT')->nullable();
            $table->integer('delivery_charge')->nullable();

            $table->dateTime('PAT')->nullable()->comment('Pick Assigned Time / Accepted Time');
            $table->dateTime('DAT')->nullable()->comment('Drop Assigned Time');
            $table->dateTime('PFC')->nullable()->comment('Picked From Customer');
            $table->dateTime('DAO')->nullable()->comment('Dropped At Office');
            $table->dateTime('PFO')->nullable()->comment('Picked From Office');
            $table->dateTime('DTC')->nullable()->comment('Delivered To Customer');
            $table->dateTime('PT')->nullable()->comment('Payment Time');
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
