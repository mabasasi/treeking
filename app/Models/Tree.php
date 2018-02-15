<?php

namespace App\Models;

use App\Exceptions\TreeCreateException;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model {

    protected $fillable = [
        'name', 'head_leaf_id', 'tail_leaf_id'
    ];

    public function leaves() {
        return $this->hasMany('App\Models\Leaf');
    }

    public function headLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'head_leaf_id');
    }

    public function tailLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'tail_leaf_id');
    }


    /**
     * 木に葉を生やして成長させる.
     * @param Leaf $leaf 葉(どこにも属していない葉)
     * @throws TreeCreateException
     */
    public function growMethod(Leaf $leaf) {
        if ($leaf->id == 0) {
            throw new TreeCreateException('leaf が保存されていません.');
        }
        if ($leaf->tree_id !== null) {
            throw new TreeCreateException('leaf が既に他の tree に関連付けられています.');
        }

        $new_leaf_id = $leaf->id;
        $tail = $this->tail_leaf_id;
        $head = $this->head_leaf_id;

        if (($tail > 0) and ($head > 0)) {
            // 自身に先頭と末尾がある場合...

            // 自身の leaf の先頭ポインタをずらして、葉を生やす
            $this->head_leaf_id = $new_leaf_id;
            $this->save();

            // leaf に自身を登録し、 leaf 自身の親に以前の先頭ポインタを設定
            $leaf->tree_id        = $this->id;
            $leaf->parent_leaf_id = $head;
            $leaf->save();

        } else {
            // それ以外の場合は、新規に葉を生やす...

            // 自身の leaf のポインタを設定
            $this->head_leaf_id = $new_leaf_id;
            $this->tail_leaf_id = $new_leaf_id;
            $this->save();

            // leaf に自身を登録し、 leaf 自身の親に念のため空ポインタを設定
            $leaf->tree_id        = $this->id;
            $leaf->parent_leaf_id = null;
            $leaf->save();
        }

    }

    public

}
