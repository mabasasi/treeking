<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Illuminate\Http\Request;

class TestController extends Controller {

    public function __invoke() {
        $trees = Tree::all();
        return view('page.test')->with(compact('trees'));
    }

}
