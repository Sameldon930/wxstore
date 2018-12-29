<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->tinyInteger('level')->unsigned()->nullable()->comment('等级 1平台 100代理 200商户');
            $table->integer('aid')->default(\App\User::DEFAULT_PLATFORM_ID)->comment('上级ID');
            $table->text('aids')->nullable()->comment('所有上级ID');
            $table->tinyInteger('status')->default(1)->comment('账户状态 1正常 2冻结');
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
