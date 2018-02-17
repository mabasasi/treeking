<?php

namespace App\Models;

use App\Exceptions\TreeCreateException;

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
     * @return Leaf 引数の leaf
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
        $head = $this->head_leaf_id;
        $tail = $this->tail_leaf_id;

        if ($this->head_leaf_id and $this->tail_leaf_id) {
            // 自身に先頭と末尾がある場合...
            $head_leaf = Leaf::findOrFail($this->head_leaf_id);

            // 自身の leaf の先頭ポインタをずらして、葉を生やす
            $this->head_leaf_id = $new_leaf_id;
            $this->save();

            // leaf に自身を登録し、 leaf 自身の親に以前の先頭ポインタを設定
            // 以前の head leaf が自身の tree 所属である場合は親とする
            $leaf->tree_id        = $this->id;
            if ($head_leaf->tree_id === $leaf->tree_id) {
                $leaf->parent_leaf_id = $head_leaf->id;
                $leaf->origin_leaf_id = null;
            } else {
                $leaf->parent_leaf_id = null;
                $leaf->origin_leaf_id = $head_leaf->id;
            }
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
            $leaf->origin_leaf_id = null;
            $leaf->save();
        }

        return $leaf;
    }


    /**
     * 木に別の木の葉を生やして成長させる.
     * (このメソッドの後に実を付ける必要あり)
     * @param Leaf $leaf どこかの tree に生えている葉
     * @return Leaf 新規に生やした葉
     * @throws TreeCreateException
     */
    public function graftMethod(Leaf $leaf) {
        if ($leaf->id == 0) {
            throw new TreeCreateException('leaf が保存されていません.');
        }
        if ($leaf->tree_id === null) {
            throw new TreeCreateException('tree に関連付けられている leaf である必要があります.');
        }
        if ($leaf->tree_id === $this->id) {
            throw new TreeCreateException('別の tree に関連付けられている leaf である必要があります.');
        }

        // 実がない葉を自身の木に生やす
        $newLeaf = Leaf::create();
        $this->growMethod($newLeaf);

        // 新たな leaf に分岐前ポインタを付与
        $newLeaf->origin_leaf_id = $leaf->id;
        $newLeaf->save();

        return $newLeaf;
    }

}
