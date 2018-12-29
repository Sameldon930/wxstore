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
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->string('order_no', 50)->unique()->default('')->comment('内部订单号');
            $table->string('out_order_no', 50)->default('')->comment('外部订单号');
            $table->string('body', 50)->default('')->comment('名称');
            $table->integer('channel_id')->unsigned()->comment('渠道ID');
            $table->tinyInteger('status')->unsigned()->comment('订单状态');
            $table->tinyInteger('pay_status')->unsigned()->comment('订单支付状态');
            $table->decimal('trade_amount', 12, 2)->nullable()->comment('交易金额');
            $table->decimal('real_amount', 12, 2)->nullable()->comment('实际支付金额');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->text('note')->nullable()->comment('备注');
            $table->text('request')->nullable()->comment('请求数据');
            $table->text('response')->nullable()->comment('响应数据');
            $table->longText('snap')->nullable()->comment('快照');
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
