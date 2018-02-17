<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/17
 * Time: 22:21
 */






if (! function_exists('git_commit')) {

    /**
     * git の head を取得する.
     * @param string $path git のパス( ../.git/logs/HEAD )
     * @return array|null 存在するなら array
     */
    function git_commit($path = '../.git/logs/HEAD') {
        $data = file($path);
        $line = $data[count($data)-1];

        if (strstr($line, '<')) {
            $split = preg_split( "/[ \t\n<>]/", $line);

            $carbon = \Carbon\Carbon::createFromFormat('U', $split[6], 'UTC');
            $carbon->setTimezone(config('app.timezone'));

            $array['parent_hash']  = $split[0];
            $array['current_hash'] = $split[1];
            $array['author']       = $split[2];
            $array['mail']         = $split[4];
            $array['date']         = $carbon;
            $array['message']      = $split[9];

            return $array;
        }
        return null;
    }

}


if (! function_exists('git_branch')) {

    /**
     * git の branch name を取得する.
     * @param string $path git のパス( ../.git/HEAD )
     * @return string|null 存在するなら array
     */
    function git_branch($path = '../.git/HEAD') {
        $data = file($path);
        $line = $data[count($data)-1];
        if ($line) {
            return trim(mb_substr($line, 16));
        }
        return null;
    }

}



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