<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller {

    public function __construct() {
        // TODO debug 環境の制限を付ける
    }

    public function dbSeed() {
        $exec = \Artisan::call('db:seed');
        return back();
    }

    public function dbMigrationFresh() {
        $exec = \Artisan::call('migrate:fresh');
        return back();
    }

}
