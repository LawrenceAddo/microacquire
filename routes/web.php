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

Route::get('/profile', 'PagesController@seller');
Route::get('/edit-profile', 'PagesController@edit')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/verified', 'PagesController@verified');

Route::get('/users/profile/view', 'ProfileController@sellerView')->name('seller_view');
Route::get('/users/profile/edit', 'ProfileController@sellerEdit')->name('seller_edit');
Route::post('/users/profile/save', 'ProfileController@sellerSave')->name('seller_save');

Route::post('/upload/temp', 'ResourcesController@uploadToTemp')->name('upload_temp');

Route::get('/res/selling/{id}/{sub_path}', 'ResourcesController@getSellerSpecific')->where('sub_path', '.*');
