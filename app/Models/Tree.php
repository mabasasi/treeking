<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model {

    protected $fillable = [
        'name', 'head_leaf_id', 'tail_leaf_id'
    ];

    public function leaves() {
        return $this->hasMany('App\Models\Leaf');
    }

    public function headLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'parent_leaf_id');
    }

    public function tailLeaf() {
        return $this->belongsTo('App\Models\Leaf', 'tail_leaf_id');
    }

}
