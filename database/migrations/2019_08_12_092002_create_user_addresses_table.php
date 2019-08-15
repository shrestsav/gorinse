<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('name',280)->nullable();            
            $table->smallInteger('area_id')->nullable();            
            $table->string('map_coordinates',480)->nullable();            
            $table->string('building_community')->nullable();            
            $table->smallInteger('type')->nullable()->comment('See Config');            
            $table->string('appartment_no')->nullable();            
            $table->text('remarks')->nullable(); 
            $table->smallInteger('is_default')->default(0);              
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
        Schema::dropIfExists('user_addresses');
    }
}
