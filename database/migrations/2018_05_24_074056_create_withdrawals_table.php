<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->string('order_no')->default('')->comment('内部订单号');
            $table->string('out_order_no')->default('')->comment('内部订单号');
            $table->bigInteger('trade_amount')->nullable()->comment('交易金额');
            $table->bigInteger('real_amount')->nullable()->comment('实际金额');
            $table->tinyInteger('type')->unsigned()->comment('提现类型');
            $table->tinyInteger('status')->unsigned()->comment('提现状态');
            $table->text('note')->nullable()->comment('操作日志');
            $table->text('refuse_reason')->nullable()->comment('拒绝理由');
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
        Schema::dropIfExists('withdrawals');
    }
}
