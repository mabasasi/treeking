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
     * @throws \Illuminate\Validation\ValidationException
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
        LeafType::create(['id' => Consts::FRUIT_TYPE_PLANE, 'name' => '標準']);

        $branch_alpha = Branch::create(['name' => 'ALPHA']);

        $sprig_a = $branch_alpha->growWithBearMethod('A', [
            'content' => 'AAAAA',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



    }

}
