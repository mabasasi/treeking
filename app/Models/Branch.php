<?php

namespace App\models;

use App\Exceptions\TreeCreateException;

class Branch extends Model {

    protected $fillable = [
        'name', 'head_sprig_id', 'tail_sprig_id'
    ];

    public function getIsEmptyAttribute() {
        return optional($this->sprigs)->isEmpty();
    }

    public function sprigs() {
        return $this->hasMany('App\Models\Sprig');
    }

    public function headSprig() {
        return $this->belongsTo('App\Models\Sprig', 'head_sprig_id');
    }

    public function tailSprig() {
        return $this->belongsTo('App\Models\Sprig', 'tail_sprig_id');
    }


    /**
     * この branch に sprig を生やす.
     * @param string $sprigName 名前
     * @return Sprig 作成した sprig
     */
    public function growMethod(string $sprigName) {
        // とりあえず 枝 を作る
        $newSprig = Sprig::create([
            'name'      => $sprigName,
            'branch_id' => $this->id,
        ]);

        // 作成した 枝 をどこに生やすか計算する
        $parent_sprig_id = null;
        $origin_sprig_id = null;

        // head と tail が存在するなら head の先に生やす
        if ($this->head_sprig_id and $this->tail_sprig_id) {

            // 先頭ポインタを新たに作成した 枝 に変更 (cache 対策で再取得)
            $headSprig = Sprig::findOrFail($this->head_sprig_id);
            $this->fill([
                'head_sprig_id' => $newSprig->id,
            ])->save();

            // 新たに作成した 枝 のパラメタ計算 (ramify 考慮)
            if ($headSprig->branch_id === $this->id) {
                // head の枝が 自身の branch の場合は parent とする
                $parent_sprig_id = $headSprig->id;
                $origin_sprig_id = null;

            } else {
                // それ以外は origin とする
                $parent_sprig_id = null;
                $origin_sprig_id = $headSprig->id;
            }

        } else {
            // 存在しないば場合、ポインタを新たに作成した 枝 に変更 (cache 対策で再取得)
            $this->fill([
                'head_sprig_id' => $newSprig->id,
                'tail_sprig_id' => $newSprig->id,
            ])->save();
        }

        // 新たに作成した 枝 に親のパラメタを設定
        $newSprig->fill([
            'parent_sprig_id' => $parent_sprig_id,
            'origin_sprig_id' => $origin_sprig_id,
        ])->save();

        return $newSprig;
    }


    /**
     * この branch に 別の branch の leaf から sprig を生やす. (merge)
     * @param string $sprigName 名前
     * @param Sprig $originSprig 疑似親要素となる別の branch の sprig
     * @return Sprig 作成した sprig
     * @throws TreeCreateException 別の branch に関連付けられている sprig である必要があります.
     */
    public function graftMethod(string $sprigName, Sprig $originSprig) {
        if ($originSprig->branch_id === null or $originSprig->branch_id === $this->id) {
            throw new TreeCreateException('別の branch に関連付けられている sprig である必要があります.');
        }

        // とりあえず自身の 幹 に 枝 を生やす
        $newSprig = $this->growMethod($sprigName);

        // 生やした 枝 に origin を付与する
        $newSprig->fill([
            'origin_sprig_id' => $originSprig->id,
        ])->save();

        return $newSprig;
    }

    /**
     * [utility] この branch に sprig を生やし leaf を付ける.
     * @param string $sprigName sprig の名前
     * @param array $leafParams leaf のパラメタ (leaf_type_id, revision=null, content)
     * @return Sprig 作成した sprig (leaf は sprig の current で取得)
     * @throws \Illuminate\Validation\ValidationException leaf のバリデーション失敗
     */
    public function growAndBearMethod(string $sprigName, array $leafParams) {
        $sprig = $this->growMethod($sprigName);
        $leaf  = $sprig->bearMethod($leafParams);

        return $sprig;
    }

}
