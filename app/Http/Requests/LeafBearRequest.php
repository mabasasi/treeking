<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeafBearRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'title'         => 'required_without:content|string|max:255',
            'content'       => 'required_without:title|string|max:65535',
            'fruit_type_id' => 'required|exists:fruit_types,id',
            'leaf_id'       => 'required|exists:leaves,id',
        ];
    }

}
