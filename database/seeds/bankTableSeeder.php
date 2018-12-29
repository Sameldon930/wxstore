<?php

use Illuminate\Database\Seeder;

class bankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bank')->insert([
            'id' => 1,
            'bank_code' => 'CBC',
            'bank_name' => '建设银行',
            'detail' => "建设银行",
            'img' => 'bank-2017-06-08-15-09-40-5938f834dba10.png',
            'order' => 1,
            'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 2,
                'bank_code' => 'BC',
                'bank_name' => '中国银行',
                'detail' => "中国银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
            ]);
        DB::table('bank')->insert([
                'id' => 3,
                'bank_code' => 'ABC',
                'bank_name' => '农业银行',
                'detail' => "农业银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
            ]);
        DB::table('bank')->insert([
                'id' => 4,
                'bank_code' => 'ICBC',
                'bank_name' => '工商银行',
                'detail' => "工商银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
            ]);
        DB::table('bank')->insert([
                'id' => 5,
                'bank_code' => 'CMSB',
                'bank_name' => '民生银行',
                'detail' => "民生银行",
                'img' => 'bank-2017-06-08-15-13-11-5938f90733bb9.png',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 6,
                'bank_code' => 'AAA',
                'bank_name' => '平安银行',
                'detail' => "平安银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 7,
                'bank_code' => 'AAA',
                'bank_name' => '招商银行',
                'detail' => "招商银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 8,
                'bank_code' => 'AAA',
                'bank_name' => '中信银行',
                'detail' => "中信银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 9,
                'bank_code' => 'AAA',
                'bank_name' => '兴业银行',
                'detail' => "兴业银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 10,
                'bank_code' => 'AAA',
                'bank_name' => '光大银行',
                'detail' => "光大银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 11,
                'bank_code' => 'AAA',
                'bank_name' => '浦发银行',
                'detail' => "浦发银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 12,
                'bank_code' => 'AAA',
                'bank_name' => '华夏银行',
                'detail' => "华夏银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 13,
                'bank_code' => 'AAA',
                'bank_name' => '北京银行',
                'detail' => "北京银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 14,
                'bank_code' => 'AAA',
                'bank_name' => '广发银行',
                'detail' => "广发银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 15,
                'bank_code' => 'AAA',
                'bank_name' => '邮储银行',
                'detail' => "邮储银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 16,
                'bank_code' => 'AAA',
                'bank_name' => '上海银行',
                'detail' => "上海银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);
        DB::table('bank')->insert([
                'id' => 17,
                'bank_code' => 'AAA',
                'bank_name' => '交通银行',
                'detail' => "交通银行",
                'img' => '',
                'order' => 1,
                'status' => 1,
        ]);

    }
}
