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
            $table->string('pick_location')->nullable();
            $table->datetime('pick_datetime')->nullable();
            $table->string('drop_location')->nullable();
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
