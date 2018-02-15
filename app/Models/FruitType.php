<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FruitType extends Model {

    protected $fillable = [
        'name'
    ];

    public function fruits() {
        return $this->hasMany('App\Models\Fruit');
    }

}
