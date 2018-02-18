<?php

namespace App\Http\Controllers;

use App\models\Branch;
use Illuminate\Http\Request;

class TestController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function __invoke() {
        $branches = Branch::all();
        return view('page.test')->with(compact('branches'));
    }

}
