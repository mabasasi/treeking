<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/17
 * Time: 22:21
 */








// Blade Helpers

if (! function_exists('out_if_true')) {

    /**
     * bool が true の時に出力するメソッド.
     * @param $bool 比較値
     * @param string $val true の出力値
     * @param string $default false の出力値 nullable
     * @return string 出力文字列
     */
    function out_if_true($bool, string $val, string $default = '') {
        return ($bool) ? $val : $default;
    }
}