<?php

namespace App\Libraries\ModelTrait;

use Illuminate\Database\Eloquent\Collection;

/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2017/10/23
 * Time: 0:14
 */
trait RelationTrait {

    /**
     * hasManyなどの関係先を文字列として取得する.
     * @param string $relation リレーション名
     * @param string $key 探索カラム
     * @param string $joinStr 結合文字列
     * @return null|string 結合文字列 nullable
     */
    public function hasManyImplode(string $relation, string $key = 'name', string $joinStr = ', ') {
        $ary = $this[$relation];
        if ($ary instanceof Collection) {
            if ($ary->count() > 0) {
                return $ary->sortBy('id')->implode($key, $joinStr);
            }
        }
        return null;
    }

    /**
     * 関係先に値が存在するか
     * @param string $relation リレーション名
     * @param string $value 値
     * @param string $key 探索カラム
     * @return bool trueで存在
     */
    public function hasManyContains(string $relation, string $value, string $key = 'id') {
        $ary = $this[$relation];
        if ($ary instanceof Collection) {
            return $ary->contains($key, $value);
        }
        return false;
    }


}