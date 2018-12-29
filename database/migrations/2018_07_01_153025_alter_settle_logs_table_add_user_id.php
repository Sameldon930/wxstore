<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettleLogsTableAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settle_logs', function (Blueprint $table){
            $table->integer('user_id')->nullable()->after('tube_id')->comment('用户ID 通道结算单有 商户结算单为空')->change();
            $table->longText('snap')->nullable()->after('settle_no')->comment('快照');
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
