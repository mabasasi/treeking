<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreeGrowRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'branch_id' => 'required|exists:branches,id',
            'name'      => 'nullable|string|max:255',
        ];
    }

}