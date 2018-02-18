<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function dbSeed() {
        $exec = \Artisan::call('db:seed', ['--class' => 'TestDataSeeder']);
        return back();
    }

    public function dbMigrationFresh() {
        $exec = \Artisan::call('migrate:fresh');
        return back();
    }

}
