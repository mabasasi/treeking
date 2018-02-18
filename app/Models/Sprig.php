<?php

namespace App\models;

class Sprig extends Model {

    protected $fillable = [
        'name', 'branch_id', 'parent_sprig_id', 'origin_sprig_id', 'current_leaf_id'
    ];


    public function is_join(Branch $branch = null) {
        if (is_null($branch))   return false;
        return $this->branch_id === $branch->id;
    }

    public function getIsEmptyAttribute() {
        return optional($this->leaves)->isEmpty();
    }

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

    public function getHasOriginAttribute() {
        return $this->originSprig !== null;
    }

    public function getHasInsertAttribute() {
        return optional($this->insertSprigs)->count() > 0;
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

    public function childSprigs() {
        return $this->hasMany('App\Models\Sprig', 'parent_sprig_id');
    }

    public function insertSprigs() {
        return $this->hasMany('App\Models\Sprig', 'origin_sprig_id');
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
        // 自身の 枝 を基準に新たに 幹 を作る
        $newBranch = Branch::create([
            'name'          => $branchName,
            'head_sprig_id' => $this->id,
            'tail_sprig_id' => $this->id,
        ]);

        return $newBranch;
    }


    /**
     * この sprig から leaf を生やす.
     * @param array $params leaf のパラメタ (leaf_type_id, revision=null, content)
     * @return Leaf 作成した leaf
     * @throws \Illuminate\Validation\ValidationException leaf のバリデーション失敗
     */
    public function bearMethod(array $params) {
        \Validator::make($params, [
            'leaf_type_id' => 'required|exists:leaf_types,id',
            'revision'     => 'nullable|string|max:255',
            'content'      => 'required|string|max:65535',
        ])->validate();

        // 自身の 枝 を基準に新たに 葉 を作る
        $params['sprig_id'] = $this->id;
        $newLeaf = Leaf::create($params);

        // 自身が参照する 葉 を変更する
        $this->fill([
            'current_leaf_id' => $newLeaf->id,
        ])->save();

        return $newLeaf;
    }

}
