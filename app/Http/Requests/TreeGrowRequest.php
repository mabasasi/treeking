<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreeGrowRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'title'         => 'required|string|max:255',
            'content'       => 'required|string|max:65535',
            'fruit_type_id' => 'required|exists:fruit_types,id',
            'tree_id'       => 'required|exists:trees,id',
        ];
    }

}