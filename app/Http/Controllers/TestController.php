<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Illuminate\Http\Request;

class TestController extends Controller {

    public function __invoke() {
        $doSeed = (request('seed') === 'true');
        if ($doSeed) {
            $exec = \Artisan::call('db:seed');
        }

        $trees = Tree::all();
        return view('page.test')->with(compact('trees'));
    }

}
