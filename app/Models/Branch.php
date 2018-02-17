<?php

namespace App\models;

class Branch extends Model {

    protected $fillable = [
        'name', 'head_sprig_id', 'tail_sprig_id'
    ];

    public function sprigs() {
        return $this->hasMany('App\Models\Sprig');
    }

    public function headSprig() {
        return $this->belongsTo('App\Models\Sprig', 'head_sprig_id');
    }

    public function tailSprig() {
        return $this->belongsTo('App\Models\Sprig', 'tail_sprig_id');
    }

}
