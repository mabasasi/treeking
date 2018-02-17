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

        $branch_alpha = Branch::create(['name' => 'BRANCH_ALPHA']);


        $sprig_a = $branch_alpha->growWithBearMethod('A', [
            'content' => 'AAAAA',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_b = $branch_alpha->growWithBearMethod('B', [
            'content' => 'BBBBB',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_c = $branch_alpha->growWithBearMethod('C', [
            'content' => 'CCCCC',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        $leaf_bb = $sprig_b->bearMethod([
            'content' => 'modify B',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        $sprig_d = $branch_alpha->growWithBearMethod('D', [
            'content' => 'DDDDD',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        $branch_beta = $sprig_d->ramifyMethod('BRANCH_BRAVO');

        $sprig_e = $branch_beta->growWithBearMethod('E', [
            'content' => 'EEEEE',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



    }

}
