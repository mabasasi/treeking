<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrowAndBearRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'branch_id'    => 'required|exists:branches,id',
            'sprig_name'   => 'required:content|string|max:255',

            'leaf_type_id' => 'required|exists:leaf_types,id',
            'revision'     => 'nullable|string|max:255',
            'content'      => 'required|string|max:65535',
        ];
    }

}
