<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardCreditBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_credit_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name')->comment('姓名');
            $table->text('url')->nullable()->comment('图片');
            $table->integer('order')->nullable()->comment('排序');
            $table->integer('status')->nullable();
            $table->string('bank_short_name')->nullable()->comment('姓名');
            $table->integer('day_limit')->nullable()->comment('单日限额');
            $table->integer('single_limit')->nullable()->comment('单笔限额');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_credit_banks');
    }
}
