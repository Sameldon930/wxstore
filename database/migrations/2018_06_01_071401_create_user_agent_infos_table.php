<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAgentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_agent_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->tinyInteger('type')->comment('账户类型 1：个人 2：个体工商户 3：公司');
            $table->tinyInteger('status')->default(1)->comment('审核状态 1：审核中 2：已审核 3：已拒绝');
            $table->text('reject_reason')->nullable()->comment('拒绝理由');

            $table->string('company_name')->nullable()->comment('公司营业名称');
            $table->string('company_business_licence')->nullable()->comment('公司营业执照');
            $table->string('company_account')->nullable()->comment('公司对公账户');

            $table->string('legal_name')->nullable()->comment('法人姓名');
            $table->string('legal_idcard')->nullable()->comment('法人身份证号');
            $table->string('legal_idcard_front')->nullable()->comment('法人身份证正面');
            $table->string('legal_idcard_back')->nullable()->comment('法人身份证反面');


            $table->string('manager_name')->nullable()->comment('负责人姓名');
            $table->string('manager_mobile')->nullable()->comment('负责人手机号');

            $table->string('cleaner_name')->nullable()->comment('结算人姓名');
            $table->string('cleaner_mobile')->nullable()->comment('结算人手机号');
            $table->string('cleaner_deposit')->nullable()->comment('结算卡');
            $table->string('cleaner_idcard')->nullable()->comment('结算人身份证号');
            $table->string('cleaner_idcard_front')->nullable()->comment('结算人身份证正面');
            $table->string('cleaner_idcard_back')->nullable()->comment('结算人身份证反面');
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
        Schema::dropIfExists('user_agent_infos');
    }
}
