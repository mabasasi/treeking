<?php

namespace App\Models;

use App\Exceptions\TreeCreateException;
use Illuminate\Database\Eloquent\Model;

class Leaf extends Model {

    protected $fillable = [
        'tree_id', 'parent_leaf_id', 'origin_leaf_id', 'current_fruit_id'
    ];

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
        // TODO 一つだけのはず
        return $this->hasOne('App\Models\leaf', 'origin_leaf_id');
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
    }

}
