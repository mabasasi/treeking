<?php

namespace App\Libraries\ModelTrait;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: morishige
 * Date: 2017/10/21
 * Time: 16:49
 */
trait SelectBoxArrayTrait {

    /**
     * セレクトボックス用の配列を作成するためのメソッド.
     * id = 0 は予約されています.
     * @return array key value
     */
    protected function getKeyValue() {
        $id = $this->id;
        $title = $this->name ?? $this->title ?? $this->id;
        return [$id => $title];
    }

    /**
     * セレクトボックス用の配列を作成する.
     * @param $query クエリ
     * @param bool $hasNone true で未選択を追加
     * @param callable $closure ($model) [$key => $value]
     * @return array セレクトボックス用の配列
     */
    public function scopeSelectPluck($query, bool $hasNone = false, callable $closure = null) {
        $selects = [];
        if ($hasNone) {
            $selects += [0 => '■選択してください。'];
        }


        $all = $query->get();
        foreach ($all as $item) {
            $selects += ($closure == null)
                ? $item->getKeyValue()
                : $closure($item);
        }

        return $selects;
    }

}