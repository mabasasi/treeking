<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreeRamifyRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'sprig_id' => 'required|exists:sprigs,id',

            // TODO いずれはユーザーごとに一意
            'name'     => 'required|string|max:255|unique:branches,name',
        ];
    }

}
