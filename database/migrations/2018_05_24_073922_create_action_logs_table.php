<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->comment('管理员ID');
            $table->tinyInteger('type')->unsigned()->comment('操作类型');
            $table->text('url')->nullable()->comment('操作地址');
            $table->text('data')->nullable()->comment('操作数据');
            $table->text('note')->nullable()->comment('操作说明');
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
        Schema::dropIfExists('action_logs');
    }
}
