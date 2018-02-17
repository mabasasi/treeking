<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/16
 * Time: 3:28
 */

namespace App\Validators;


use Illuminate\Validation\Validator;

class DatabaseValidator extends Validator {

    public function validateExistsOrNull($attribute, $value, $parameters) {
        if($value == 0 || is_null($value)) {
            return true;
        } else {
            $validator = \Validator::make([$attribute => $value], [
                $attribute => 'exists:' . implode(",", $parameters)
            ]);
            return !$validator->fails();
        }
    }


    public function validateEqual($attribute, $value, $parameters) {
        $diff = data_get($parameters, '0');
        return ($diff and $diff === $value);
    }

}