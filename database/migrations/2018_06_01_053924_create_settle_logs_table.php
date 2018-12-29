<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settle_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('tube_id')->comment('通道ID');
            $table->tinyInteger('status')->unsigned()->comment('结算状态');
            $table->string('settle_no')->nullable()->comment('结算号');
            $table->bigInteger('settled_amount')->comment('已结算金额');
            $table->bigInteger('waiting_amount')->comment('待结算金额');
            $table->text('settled_orders')->nullable()->comment('已结算订单 逗号分隔');
            $table->text('waiting_orders')->nullable()->comment('待结算订单 逗号分隔');
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
        Schema::dropIfExists('settle_logs');
    }
}
