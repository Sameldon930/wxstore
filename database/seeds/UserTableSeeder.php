<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker\Factory::create('zh_CN');
        $now = \Carbon\Carbon::now();

        $tubes = [
            [
                'id' => 1,
                'name' => 'WECHAT',
                'display' => '微信',
                'channels' => [
                    ['name' => 'WECHAT_SCAN', 'display' => '微信扫码', 'id' => 1,],
                    ['name' => 'WECHAT_BAR', 'display' => '微信刷卡', 'id' => 2,],
                    ['name' => 'WECHAT_JS', 'display' => '微信公众号', 'id' => 3,],
                    ['name' => 'WECHAT_H5', 'display' => '微信H5', 'id' => 7,],
                ],
            ],
            [
                'id' => 2,
                'name' => 'ALI',
                'display' => '支付宝',
                'channels' => [
                    ['name' => 'ALI_SCAN', 'display' => '支付宝扫码', 'id' => 4,],
                    ['name' => 'ALI_BAR', 'display' => '支付宝刷卡', 'id' => 5,],
                    ['name' => 'ALI_JS', 'display' => '支付宝网页', 'id' => 6,],
                ],
            ],
        ];

        DB::table('users')->insert([
            'name' => '智世',
            'mobile' => '10000',
            'password' => bcrypt(123456),
            'level' => 2 | 32,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        \App\UserAgentInfo::create([
            'user_id' => 1,
            'type' => \App\UserAgentInfo::TYPE_COMPANY,
            'status' => \App\UserAgentInfo::CHECKED,
        ]);

        foreach ($tubes as $tube){

            \App\Tube::create([
                'id' => $tube['id'],
                'name' => $tube['name'],
                'display' => $tube['display'],
            ]);

            \App\UserAccount::create([
                'user_id' => 1,
                'tube_id' => $tube['id'],
                'balance' => 0,
                'change' => 0,
            ]);



            foreach ($tube['channels'] as $channel){
                \App\Channel::create([
                    'id' => $channel['id'], 'name' => $channel['name'], 'display' => $channel['display'], 'tube_id' => $tube['id']
                ]);

                \App\UserAgentChannel::insert([
                    'user_id' => 1,
                    'channel_id' => $channel['id'],
                    'profit_rate' => 20,
                ]);
            }
        }

        /*foreach (\App\User::LEVEL_MAP as $level => $name){
            DB::table('users')->insert([
                'name' => $name,
                'mobile' => '00' . $level,
                'password' => bcrypt(123456),
                'level' => $level,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }*/

        DB::table('admins')->insert([
            'name' => 'qch',
            'mobile' => '18850503821',
            'password' => bcrypt(123456),
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('roles')->insert([
            'name' => 'admin',
            'display' => '超级管理员',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('admin_role')->insert([
            'admin_id' => 1,
            'role_id' => 1,
        ]);


    }
}
