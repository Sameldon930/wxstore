<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settle_logs', function (Blueprint $table){
            $table->bigInteger('total_amount')->comment('总金额');
            $table->bigInteger('refund_amount')->comment('退款金额');
            $table->bigInteger('real_amount')->comment('实际金额');
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
