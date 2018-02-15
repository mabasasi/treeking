<?php

namespace App\Models;

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

}
