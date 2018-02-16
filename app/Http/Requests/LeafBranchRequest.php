<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeafBranchRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'leaf_id'   => 'required|exists:leaves,id',

            // TODO いずれはユーザーごとに一意
            'tree_name' => 'required|string|max:255|unique:trees,name',
        ];
    }

}
