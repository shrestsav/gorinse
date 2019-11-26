<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_grants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referrer_id')->unsigned();
            $table->integer('receipient_id')->unsigned();
            $table->integer('grant')->unsigned()->default(0)->comment('Granted to Referrer Account');
            $table->unique(['referrer_id', 'receipient_id']);
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
        Schema::dropIfExists('referral_grants');
    }
}
