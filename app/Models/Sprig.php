<?php

namespace App\models;

class Sprig extends Model {

    protected $fillable = [
        'name', 'branch_id', 'parent_sprig_id', 'origin_sprig_id', 'current_leaf_id'
    ];

    public function getIsRootAttribute() {
        return ($this->parent_sprig_id == null and $this->origin_sprig_id == null);
    }

    public function getIsHeadAttribute() {
        $sprig_id = optional($this->branch)->head_sprig_id;
        return ($sprig_id === $this->id);
    }

    public function getIsTailAttribute() {
        $sprig_id = optional($this->branch)->tail_sprig_id;
        return ($sprig_id === $this->id);
    }

    public function branch() {
        return $this->belongsTo('App\Models\Branch');
    }

    public function parentSprig() {
        return $this->belongsTo('App\Models\Sprig', 'parent_sprig_id');
    }

    public function originSprig() {
        return $this->belongsTo('App\Models\Sprig', 'origin_sprig_id');
    }

    public function childSprings() {
        return $this->hasMany('App\Models\Sprig', 'parent_sprig_id');
    }

    public function insertSprings() {
        return $this->hasMany('App\Models\Spring', 'origin_sprig_id');
    }

    public function leaves() {
        return $this->hasMany('App\Models\Leaf');
    }

    public function currentLeave() {
        return $this->belongsTo('App\Models\Leaf', 'current_leaf_id');
    }


    /**
     * この sprig から branch を生やす.
     * @param string $branchName 名前
     * @return Branch 作成した branch
     */
    public function ramifyMethod(string $branchName) {
        // 自身の 葉 を基準に新たに 幹 を作る
        $newBranch = Branch::create([
            'name'          => $branchName,
            'head_sprig_id' => $this->id,
            'tail_sprig_id' => $this->id,
        ]);

        return $newBranch;
    }

}
