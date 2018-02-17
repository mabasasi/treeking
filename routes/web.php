<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'TestController')->name('test');


Route::get('/treeking', 'TreekingController@index')->name('treeking.index');







Route::get('/debug/seed',  'DebugController@dbSeed')->name('debug.seed');
Route::get('/debug/fresh', 'DebugController@dbMigrationFresh')->name('debug.fresh');

Route::post('/action/grow',   'TreeActionController@grow')->name('action.tree.grow');
Route::post('/action/bear',   'TreeActionController@bear')->name('action.tree.bear');
Route::post('/action/plant',  'TreeActionController@plant')->name('action.tree.plant');
Route::post('/action/ramify', 'TreeActionController@ramify')->name('action.tree.ramify');
Route::post('/action/graft',  'TreeActionController@graft')->name('action.tree.graft');