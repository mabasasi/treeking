<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreeGraftRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'sprig_id'  => 'required|exists:sprigs,id',
            'branch_id' => 'required|exists:branches,id',

            'name'      => 'required|string|max:255',
        ];
    }

}