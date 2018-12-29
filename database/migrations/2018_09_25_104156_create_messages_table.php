<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('消息标题');
            $table->string('thumbnail')->comment('缩略图');
            $table->text('content')->comment('内容');
            $table->unsignedInteger('top')->default(2)->comment('是否置顶,默认为2，不置顶');
            $table->unsignedInteger('orderby')->nullable()->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态值：默认为1，为显示状态');
            $table->timestamp('now')->nullable()->comment('发布时间');
            $table->tinyInteger('is_read')->default(0)->comment('阅读状态:0未读,1已读');
            $table->string('text')->comment('消息简介');
            $table->softDeletes();
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
        Schema::dropIfExists('messages');
    }
}
