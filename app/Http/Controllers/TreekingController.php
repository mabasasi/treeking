<?php

namespace App\Http\Controllers;

use App\models\Sprig;
use Illuminate\Http\Request;

class TreekingController extends Controller {

    public function index() {
        $sprigs = Sprig::createdNewer()->with('leaves')->get();

        return view('treeking.index')->with(compact('sprigs'));
    }

}
