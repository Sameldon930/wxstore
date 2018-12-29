<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardSavingsBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_savings_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_id')->comment('银行卡id');
            $table->string('bank_code')->comment('行号');
            $table->string('url')->comment('图片路径');
            $table->string('bank_short_name')->comment('银行卡简称');
            $table->string('bank_address_code');
            $table->string('bank_name')->comment('银行名称');
            $table->string('bank_province')->comment('银行省市');
            $table->string('bank_city')->comment('银行城市');
            $table->string('bank_address')->comment('银行地址');
            $table->smallInteger('order')->nullable()->comment('排序');
            $table->tinyInteger('status')->nullable()->comment('状态');
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable()->comment('状态');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_savings_banks');
    }
}
