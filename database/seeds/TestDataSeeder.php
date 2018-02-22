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
     * @throws \App\Exceptions\TreeCreateException
     */
    public function run() {
        // 全部消す！
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Branch::truncate();
        Sprig::truncate();
        Leaf::truncate();
        LeafType::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ユーザーがいたらすべての初期ブランチを削除
        foreach (\App\Models\User::all() as $user) {
            $user->fill(['current_branch_id' => null])->save();
        }

        // テストケースを作成する
        LeafType::create(['id' => Consts::FRUIT_TYPE_PLANE, 'name' => '標準']);



        // TODO test plant
        $branch_alpha = Branch::create(['name' => 'BRANCH_ALPHA']);


        // test grow and bear
        $sprig_a = $branch_alpha->growAndBearMethod('A', [
            'content' => 'AAAAA',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_b = $branch_alpha->growAndBearMethod('B', [
            'content' => 'BBBBB',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_c = $branch_alpha->growAndBearMethod('C', [
            'content' => 'CCCCC',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test bear of leaf
        $leaf_bb = $sprig_b->bearMethod([
            'content' => 'modify B',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // dummy
        $sprig_d = $branch_alpha->growAndBearMethod('D', [
            'content' => 'DDDDD',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test ramify
        $branch_beta = $sprig_d->ramifyMethod('BRANCH_BRAVO');

        $sprig_e = $branch_beta->growAndBearMethod('E', [
            'content' => 'EEEEE',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // TODO test plant
        $branch_charlie = Branch::create(['name' => 'BRANCH_CHARLIE']);

        $sprig_f = $branch_charlie->growAndBearMethod('F', [
            'content' => 'FFFFF',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_g = $branch_charlie->growAndBearMethod('G', [
            'content' => 'GGGGG',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test graft and bear
        $sprig_h = $branch_beta->graftMethod('H', $sprig_g);
        $sprig_h->bearMethod([
            'content' => 'HHHHH  marge GG.',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // test graft and bear
        $sprig_i = $branch_alpha->graftMethod('I', $sprig_h);
        $sprig_i->bearMethod([
            'content' => 'IIIII  marge HH.',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);



        // dummy
        $sprig_j = $branch_alpha->growAndBearMethod('J', [
            'content' => 'JJJJJ',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

        $sprig_k = $branch_charlie->growAndBearMethod('K', [
            'content' => 'KKKKK',
            'leaf_type_id' => Consts::FRUIT_TYPE_PLANE,
        ]);

    }

}
