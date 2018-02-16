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

Route::post('/action/grow',   'TreeActionController@growTree')->name('action.tree.grow');
Route::post('/action/bear',   'TreeActionController@bearLeaf')->name('action.leaf.bear');
Route::post('/action/plant',  'TreeActionController@plantTree')->name('action.tree.plant');
Route::post('/action/branch', 'TreeActionController@branchLeaf')->name('action.leaf.branch');