<?php

use Illuminate\Database\Seeder;
use App\Consts;
use App\Models\FruitType;
use App\Models\Fruit;
use App\Models\Leaf;
use App\Models\Tree;

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
        Tree::truncate();
        Leaf::truncate();
        Fruit::truncate();
        FruitType::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        FruitType::create(['id' => \App\Consts::FRUIT_TYPE_PLANE, 'name' => 'plane']);


        $tree_x = Tree::create(['name' => 'Xの木']);
        $tree_y = Tree::create(['name' => 'Yの木']);


        $leaf_a = Leaf::create();

        $fruit_a = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実A',
            'content' => '実Aの中身です',
        ]);

        $leaf_a->bearMethod($fruit_a);
        $tree_x->growMethod($leaf_a);


        ////////////////////////////////////////


        $leaf_b = Leaf::create();

        $fruit_b = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実B',
            'content' => '実Bの中身です'
        ]);

        $leaf_b->bearMethod($fruit_b);
        $tree_x->growMethod($leaf_b);


        $fruit_bb = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実B(修正版)',
            'content' => '実Bの中身を変更しました'
        ]);

        $leaf_b->bearMethod($fruit_bb);


        ////////////////////////////////////////


        $leaf_c = Leaf::create();

        $fruit_c = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実C',
            'content' => '実Cの中身です'
        ]);

        $leaf_c->bearMethod($fruit_c);
        $tree_x->growMethod($leaf_c);


        ////////////////////////////////////////


        $leaf_d = Leaf::create();

        $fruit_d = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実D',
            'content' => '実Dの中身です',
        ]);

        $leaf_d->bearMethod($fruit_d);
        $tree_y->growMethod($leaf_d);


        ////////////////////////////////////////


        $leaf_e = Leaf::create();

        $fruit_e = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実E',
            'content' => '実Eの中身です'
        ]);

        $leaf_e->bearMethod($fruit_e);
        $tree_y->growMethod($leaf_e);


        ////////////////////////////////////////


        $leaf_f = Leaf::create();

        $fruit_f = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実F',
            'content' => '実Fの中身です'
        ]);

        $leaf_f->bearMethod($fruit_f);
        $tree_y->growMethod($leaf_f);


        ////////////////////////////////////////

        // grant test
        $leaf_g = $tree_x->graftMethod($leaf_e);

        $fruit_g = Fruit::create([
            'fruit_type_id' => Consts::FRUIT_TYPE_PLANE,
            'title' => '実G',
            'content' => '本当は D E のまとめを編集して載せる'
        ]);

        $leaf_g->bearMethod($fruit_g);

    }
}
