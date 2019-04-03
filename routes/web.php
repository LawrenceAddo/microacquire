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

/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', 'PagesController@home');

// Route::get('/profile', 'PagesController@seller');
Route::get('/edit-profile', 'PagesController@edit')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/verified', 'PagesController@verified');

Route::get('/profile/view', 'ProfileController@view')->name('profile_view');
Route::get('/profile/edit', 'ProfileController@edit')->name('profile_edit');
Route::post('/users/profile/save', 'ProfileController@save')->name('profile_save');

Route::post('/upload/temp', 'ResourcesController@uploadToTemp')->name('upload_temp');

Route::get('/res/selling/{id}/{sub_path}', 'ResourcesController@getSellerSpecific')->where('sub_path', '.*');
