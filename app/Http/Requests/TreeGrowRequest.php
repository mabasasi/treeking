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
            'name'      => 'required_without:content|string|max:255',
        ];
    }

}