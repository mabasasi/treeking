<?php

namespace App\Models;

class FruitType extends Model {

    protected $fillable = [
        'name'
    ];

    public function fruits() {
        return $this->hasMany('App\Models\Fruit');
    }

}
