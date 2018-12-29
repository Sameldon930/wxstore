<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTableAddddWxOpenidAndMore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table){
            $table->string('wx_openid')->nullable()->comment('微信openid');
            $table->string('wx_nickname')->nullable()->comment('微信昵称');
            $table->string('wx_headimgurl')->nullable()->comment('微信头像');
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
