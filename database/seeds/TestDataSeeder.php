<?php

use Illuminate\Database\Seeder;
use App\Consts;
use App\Models\Branch;
use App\Models\Sprig;
use App\Models\Leaf;
use App\Models\LeafType;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // 全部消す！
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Branch::truncate();
        Sprig::truncate();
        Leaf::truncate();
        LeafType::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // テストケースを作成する




    }

}
