<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fruit extends Model {

    protected $fillable = [
        'leaf_id', 'revision', 'fruit_type_id', 'title', 'content'
    ];

    public function getIsCurrentAttribute() {
        $leaf = optional($this->leaf)->current_fruit_id;
        return ($leaf === $this->id);
    }

    public function leaf() {
        return $this->belongsTo('App\Models\Leaf');
    }

    public function type() {
        return $this->belongsTo('App\Models\FruitType');
    }

}
