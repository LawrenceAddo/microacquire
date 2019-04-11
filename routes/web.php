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

Route::get('/profile/', 'ProfileController@view')->name('profile_view');
Route::get('/profile/edit', 'ProfileController@edit')->name('profile_edit');
Route::post('/profile/save', 'ProfileController@save')->name('profile_save');
Route::get('/sellers', 'ProfileController@sellers')->name('sellers');
Route::get('/sellers/search', 'ProfileController@sellersSearch')->name('sellers_search');
Route::get('/sellers/{id}', 'ProfileController@sellerViewById')->name('seller');
Route::get('/buyers', 'ProfileController@buyers')->name('buyers');
Route::get('/buyers/search', 'ProfileController@buyersSearch')->name('buyers_search');

Route::post('/upload/temp', 'ResourcesController@uploadToTemp')->name('upload_temp');

Route::get('/res/selling/{id}/{sub_path}', 'ResourcesController@getSellerSpecific')->where('sub_path', '.*');
Route::get('/qrcode', 'ResourcesController@qrcode')->name('qrcode');
Route::get('/res/avatar/empty', 'ResourcesController@emptyAvatar')->name('empty_avatar');
