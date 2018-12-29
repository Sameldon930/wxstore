<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettleLogsTableAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settle_logs', function (Blueprint $table){
            $table->tinyInteger('type')->comment('结算单类型 1：商户结算单 2：代理结算单');
            $table->bigInteger('charge_amount')->comment('手续费金额');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
