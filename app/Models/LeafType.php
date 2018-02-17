<?php

namespace App\Models;

class LeafType extends Model {

    protected $fillable = [
        'name'
    ];

    public function leaves() {
        return $this->hasMany('App\Models\Leaf', 'leaf_type_id');
    }

}
