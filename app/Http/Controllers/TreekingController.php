<?php

namespace App\Http\Controllers;

use App\models\Sprig;
use Illuminate\Http\Request;

class TreekingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $sprigs = Sprig::ifRequestWhere('branch_id', ['branch_id'])
            ->createdNewer()
            ->with('leaves')
            ->get();

        return view('treeking.index')->with(compact('sprigs'));
    }

}
