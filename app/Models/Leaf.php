<?php

namespace App\Models;

use App\Exceptions\TreeCreateException;

class Leaf extends Model {

    protected $fillable = [
        'tree_id', 'parent_leaf_id', 'origin_leaf_id', 'current_fruit_id'
    ];

    public function getIsRootAttribute() {
        return ($this->parent_leaf_id == null and $this->origin_leaf_id == null);
    }

    public function getIsHeadAttribute() {
        $leaf = optional($this->tree)->head_leaf_id;
        return ($leaf === $this->id);
    }

    public function getIsTailAttribute() {
        $leaf = optional($this->tree)->tail_leaf_id;
        return ($leaf === $this->id);
    }

    public function tree() {
        return $this->belongsTo('App\Models\tree');
    }

    public function parentLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'parent_leaf_id');
    }

    public function originLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'origin_leaf_id');
    }

    public function childLeaves() {
        return $this->hasMany('App\Models\Leaf', 'parent_leaf_id');
    }

    public function insertLeaves() {
        return $this->hasMany('App\Models\leaf', 'origin_leaf_id');
    }

    public function fruits() {
        return $this->hasMany('App\Models\Fruit');
    }

    public function currentFruit() {
        return $this->belongsTo('App\Models\Fruit', 'current_fruit_id');
    }




    /**
     * 葉に実を付ける.
     * @param fruit $fruit 実(どこにも属していない実)
     * @return Fruit 引数の fruit
     * @throws TreeCreateException
     */
    public function bearMethod(Fruit $fruit) {
        if ($fruit->id == 0) {
            throw new TreeCreateException('fruit が保存されていません.');
        }
        if ($fruit->leaf_id !== null) {
            throw new TreeCreateException('fruit が既に他の leaf に関連付けられています.');
        }

        $new_fruit_id = $fruit->id;

        // 自身の fruit の参照ポインタを変更
        $this->current_fruit_id = $new_fruit_id;
        $this->save();

        // fruit に自身を登録
        $fruit->leaf_id = $this->id;
        $fruit->save();

        return $fruit;
    }


    /**
     * 葉から別の木を成長させる.
     * (このメソッド後に実を付ける必要あり)
     * @param string $newBranchName 新たな木の名前
     * @return Leaf 新規に生やした葉
     * @throws TreeCreateException
     */
    public function branchMethod(string $newBranchName) {
        if ($this->id == 0) {
            throw new TreeCreateException('leaf が保存されていません.');
        }
        if ($this->tree_id === null) {
            throw new TreeCreateException('leaf が他の tree に関連付けていません.');
        }

        // 新たな木を生やす
        $tree = Tree::create(['name' => $newBranchName]);

        // 実がない葉を生やす
        $newLeaf = Leaf::create();
        $tree->growMethod($newLeaf);

        // 新たな leaf に分岐前ポインタを付与
        $newLeaf->origin_leaf_id = $this->id;
        $newLeaf->save();

        return $newLeaf;
    }


}
