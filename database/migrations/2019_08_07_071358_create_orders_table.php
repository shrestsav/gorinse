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
            $table->integer('driver_id')->unsigned()->nullable();
            $table->smallInteger('type')->comment('1:Normal, 2:Urgent');
            $table->smallInteger('pick_location')->nullable()->comment('From User Address Table');
            $table->datetime('pick_datetime')->nullable();
            $table->smallInteger('drop_location')->nullable()->comment('From User Address Table');
            $table->datetime('drop_datetime')->nullable();
            $table->smallInteger('status')->default(0)->comment('See Config');
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
