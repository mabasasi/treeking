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



        // TODO test plant
        $branch_alpha = Branch::create(['name' => 'BRANCH_ALPHA']);


        // test grow and bear
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



        // test bear of leaf
        $leaf_bb = $sprig_b->bearMethod([
            'content' => 'modify B',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // dummy
        $sprig_d = $branch_alpha->growWithBearMethod('D', [
            'content' => 'DDDDD',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test ramify
        $branch_beta = $sprig_d->ramifyMethod('BRANCH_BRAVO');

        $sprig_e = $branch_beta->growWithBearMethod('E', [
            'content' => 'EEEEE',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // TODO test plant
        $branch_charlie = Branch::create(['name' => 'BRANCH_CHARLIE']);

        $sprig_f = $branch_charlie->growWithBearMethod('F', [
            'content' => 'FFFFF',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_g = $branch_charlie->growWithBearMethod('G', [
            'content' => 'GGGGG',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test graft and bear
        $sprig_h = $branch_beta->graftMethod('H', $sprig_g);
        $sprig_h->bearMethod([
            'content' => 'HHHHH  marge FF and GG.',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);







    }

}
