<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/15
 * Time: 20:15
 */

namespace App\Exceptions;


use Throwable;

class TreeCreateException extends \Exception {

    public function __construct(string $message = "Failed to create a tree.", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}