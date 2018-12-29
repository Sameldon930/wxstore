<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('订单ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->bigInteger('amount')->nullable()->comment('交易金额');
            $table->bigInteger('balance')->nullable()->comment('实际金额');
            $table->tinyInteger('type')->unsigned()->comment('账变类型');
            $table->tinyInteger('flow')->unsigned()->comment('资金流向 1：转入 2：转出');
            $table->text('snap')->nullable()->comment('快照');
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
        Schema::dropIfExists('account_logs');
    }
}
