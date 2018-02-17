<?php

namespace App\Models;

class Leaf extends Model {

    protected $fillable = [
        'sprig_id', 'leaf_type_id', 'revision', 'content'
    ];

    public function getIsCurrentAttribute() {
        $sprig_id = optional($this->sprig)->current_leaf_id;
        return ($sprig_id === $this->id);
    }

    public function type() {
        return $this->belongsTo('App\Models\LeafType', 'leaf_type_id');
    }

    public function sprig() {
        return $this->belongsTo('App\Models\Sprig');
    }

}
