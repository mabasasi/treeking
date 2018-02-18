<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // 全部消す！
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 管理用 User の作成
        $pass = env('ADMIN_PASSWORD', null);
        User::create([
            'id'       => env('id', \App\Consts::ADMIN_USER_ID),
            'name'     => env('ADMIN_NAME',     null),
            'userid'   => env('ADMIN_USER_ID',  null),
            'email'    => env('ADMIN_EMAIL',    null),
            'password' => $pass ? Hash::make($pass) : null,
        ]);
    }
}
