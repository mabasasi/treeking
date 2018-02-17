<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeafBearRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'sprig_id'     => 'required|exists:sprigs,id',
            'leaf_type_id' => 'required|exists:leaf_types,id',
            'revision'     => 'nullable|string|max:255',
            'content'      => 'required|string|max:65535',
        ];
    }

}
