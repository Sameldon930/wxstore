<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('bankcode_id');
            $table->string('account_name')->comment('姓名');
            $table->string('account_no')->comment('账户');
            $table->string('credit_mobile')->nullable()->comment('信用卡预留手机号');
            $table->integer('status')->default(0);
            $table->integer('cvv2')->nullable()->comment('信用卡卡背后三位数');
            $table->integer('indate')->nullable()->comment('信用卡有效期 月年');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_credit_cards');
    }
}
