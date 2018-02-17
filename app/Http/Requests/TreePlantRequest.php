<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreePlantRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            // TODO いずれはユーザーごとに一意
            'name' => 'required|string|max:255|unique:trees,name',
        ];
    }

}
