<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserMerchantInfosTableAddCompanyName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_merchant_infos',function(Blueprint $table){
            $table -> string('company_name')->nullable()->comment(' 商户营业名称');
            $table -> string('business_person')->nullable()->comment('法人姓名');
            $table -> text('identity_front')->nullable()->comment(' 身份证正面');
            $table -> text('identity_contrary')->nullable()->comment(' 身份证反面');
            $table -> text('merchant_license')->nullable()->comment(' 商户执照');
            $table -> text('restaurant_license')->nullable()->comment('餐饮许可证（可不上传');
            $table -> string('registration_number')->nullable()->comment(' 营业注册号');
            $table -> string('identity_num')->nullable()->comment('身份证号码');
            $table -> string('email')->nullable()->comment('商户邮箱');
            $table -> text('contract_tenancy')->nullable()->comment(' 门店招牌');
            $table -> text('interior_picture')->nullable()->comment('门店内景');
            $table -> string('registrantname')->nullable()->comment(' 注册者姓名 ');
            $table -> string('mobile')->nullable()->comment('注册者手机号');
            $table -> integer('status')->nullable()->comment('状态1审核中。2审核通过，3未通过');
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
