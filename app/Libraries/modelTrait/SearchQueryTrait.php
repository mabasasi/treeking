<?php

namespace App\Libraries\ModelTrait;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Created by PhpStorm.
 * User: morishige
 * Date: 2017/10/21
 * Time: 17:47
 */
trait SearchQueryTrait {


    /**
     * リクエストに値が存在するとき、取得する日付を制限する.
     * @param $query クエリ
     * @param string $requestName リクエスト内の名前
     * @param array $startSchemeNames 検索する relation or scheme　名 xxx.yyy.zzz
     * @param array $endSchemeNames 検索する relation or scheme　名 xxx.yyy.zzz
     * @return mixed クエリ
     */
    public function scopeIfRequestDate($query, string $requestName, array $startSchemeNames, array $endSchemeNames) {
        // 検索値
        $value = \Request::input($requestName);
        $isStrict = false;

        if ($value) {
            // TODO 入力値チェックしていない
            $date  = Carbon::parse($value);
            $start = $date->startOfDay();
            $end   = $date->copy()->endOfDay();

            return $query->ifWhere($start->toDateTimeString(), $endSchemeNames,   '>', false, $isStrict)
                        ->ifWhere($end->toDateTimeString(),   $startSchemeNames, '<', false, $isStrict);
        }

        return $query;
    }










    /**
     * リクエストに値が存在するとき、論理削除済みのモデルも取得する.
     * @param $query クエリ
     * @param string $requestName リクエスト内の名前
     * @param bool $trueValue 比較時の true 条件
     * @return mixed クエリ
     */
    public function scopeIfRequestShowTrashed($query, string $requestName = 'trashed', $trueValue = true) {
        // 検索値
        $value = \Request::input($requestName);

        // 存在時に比較して、withTrashed を付与するか判断
        if ($value and method_exists($this, 'bootSoftDeletes')) {
            if ($value == $trueValue) {
                return $query->withTrashed();
            }
        }

        return $query;
    }

    /**
     * リクエストに値が存在するとき、ソートする.
     * @param $query クエリ
     * @param string $key ソート対象のカラム名
     * @param string $requestName リクエスト内の名前
     * @return mixed クエリ
     */
    public function scopeIfRequestOrderBy($query, string $key, string $requestName = 'order') {
        // 検索値
        $value = \Request::input($requestName);

        // 存在時に比較して、order_by するか判断
        if ($key and $value) {
            $query->orderBy($key, $value);
        }

        return $query;
    }


    /**
     * リクエストの値を使用して paginate の数を決定する終端メソッド.
     * @param $query クエリ
     * @param string $requestName リクエスト内の名前
     * @param int $default デフォルトのpaginate値
     * @return mixed paginate collection
     */
    public function scopeRequestPaginate($query, string $requestName = 'paginate', int $default = null) {
        // 検索値
        $value = \Request::input($requestName);

        // デフォルト値はユーザーのページね～と数
        $userNum = \Auth::user()->default_pagenate_num;
        if ($userNum and $default == null) {
            $default = $userNum ?? 30;
        }

        // 存在時はデフォルト値を書き換える
        if ($value and ctype_digit($value)) {
            $default = intval($value);
        }

        return $query->paginate($default);
    }

    /**
     * リクエストに値が存在するとき、モデルの検索を行う.
     * @param $query クエリ
     * @param string $requestName リクエスト内の名前
     * @param array $schemeNames 検索する relation or scheme　名 xxx.yyy.zzz
     * @param bool $isStrict trueで厳格に比較する 0 とか
     * @param string $operator 比較時のオペレーター
     * @return mixed クエリ
     */
    public function scopeIfRequestWhere($query, string $requestName, array $schemeNames, bool $isStrict = false, string $operator = '=') {
        // 検索値
        $value = \Request::input($requestName);
        // 存在時にモデル内の検索
        if ($value or $isStrict) {
            $this->scopeIfWhere($query, strval($value), $schemeNames, $operator, false, $isStrict);
        }

        return $query;
    }

    /**
     * リクエストに値が存在するとき、モデルの検索を行う(文字列検索特化用).
     * AND検索実装.
     * @param $query クエリ
     * @param string $requestName リクエスト内の名前
     * @param array $schemeNames 検索する relation or scheme　名 xxx.yyy.zzz
     * @param bool $isStrict trueで厳格に比較する 0 とか
     * @return mixed クエリ
     */
    public function scopeIfRequestLike($query, string $requestName, array $schemeNames, bool $isStrict = false) {
        // 検索値
        $value = \Request::input($requestName);

        // 値があったときのみ where 検索を行う
        if ($value) {
            // 検索値の全角空白を半角に置換
            $value = mb_ereg_replace("　", " ", $value);

            // 検索値を and 用に分解する
            $searches = new Collection(explode(' ', $value));

            foreach ($searches as $search) {
                if ($search) {
                    // like 検索用の % を付与
                    $value = "%${search}%";

                    // 存在時にモデル内の検索（or 検索）
                    $query->where(function($sub) use($value, $schemeNames, $isStrict) {
                        $this->scopeIfWhere($sub, strval($value), $schemeNames, 'LIKE', true, $isStrict);
                    });
                }
            }
        }

        return $query;
    }

    /**
     * 値よりモデルの検索を行う.
     * @param $query クエリ
     * @param string $value 比較値
     * @param array $schemeNames 検索する relation or scheme　名 xxx.yyy.zzz
     * @param string $operator 比較時のオペレーター
     * @param bool $isMultiMode 内部の比較を or で行うモード このメソッドを where で囲む際に true を使用する
     * @param bool $isStrict trueで厳格に比較する 0 とか
     * @return mixed クエリ
     */
    public function scopeIfWhere($query, string $value, array $schemeNames, string $operator = '=', bool $isMultiMode = false, bool $isStrict = false) {
//        dump("value=${value} operator=${operator}");
        if ($value or $isStrict) {
            // マルチモード(orを使用するフラグ)
            $isInnerMultiMode = count($schemeNames) > 1;

            foreach ($schemeNames as $schemeName) {
//                dump("allScheme=${schemeName}");
                // スキーマの分解
                $schemes = new Collection(explode('.', $schemeName));

                // where 検索
                if ($isMultiMode) {
                    $query->orWhere(function ($sub) use ($value, $schemes, $operator, $isInnerMultiMode) {
                        $this->whereSearchClosure($sub, $schemes, $value, $operator, $isInnerMultiMode);
                    });
                } else {
                    $query->where(function ($sub) use ($value, $schemes, $operator, $isInnerMultiMode) {
                        $this->whereSearchClosure($sub, $schemes, $value, $operator, $isInnerMultiMode);
                    });
                }
            }
        }

        return $query;
    }


    private function whereSearchClosure($query, Collection $schemes, $value, $operator, $isMultiMode = false) {
        // コレクションの先頭を取り出す
        $scheme = $schemes->shift();
        if ($scheme) {
            if ($schemes->count()) {
                // もし scheme が relation なら where has
                if ($isMultiMode) {
                    $query->orWhereHas($scheme, function($sub) use($query, $schemes, $value, $operator) {
                        $this->whereSearchClosure($sub, $schemes, $value, $operator);
                    });
                } else {
//                    dump("where has ${scheme}");
                    $query->WhereHas($scheme, function($sub) use($query, $schemes, $value, $operator) {
                        $this->whereSearchClosure($sub, $schemes, $value, $operator);
                    });
                }
            } else {
                // それ以外なら where
                if ($isMultiMode) {
                    $query->orWhere($scheme, $operator, $value);
                } else {
                    $query->where($scheme, $operator, $value);
                }
            }
        }
    }

}