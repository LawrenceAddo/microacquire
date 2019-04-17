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


Route::get('/', 'PagesController@home')->name('home');

// Route::get('/profile', 'PagesController@seller');
// Route::get('/edit-profile', 'PagesController@edit')->middleware('verified');

Auth::routes(['verify' => true]);

Route::middleware('auth')->group(function () {

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/email/verified', 'PagesController@verified');

    Route::middleware('verified')->group(function () {

        
    
        Route::get('/profile/', 'ProfileController@view')->name('profile_view');
        Route::get('/profile/edit', 'ProfileController@edit')->name('profile_edit');
        Route::post('/profile/save', 'ProfileController@save')->name('profile_save');


        Route::get('/sellers', 'ProfileController@sellers')->name('sellers');
        Route::get('/sellers/search', 'ProfileController@sellersSearch')->name('sellers_search');
        Route::get('/sellers/{id}', 'ProfileController@sellerViewById')->name('seller');
        Route::get('/sellers/{id}/edit', 'ProfileController@sellerEditById')->name('seller_edit');
        Route::post('/sellers/{id}', 'ProfileController@sellerSaveById')->name('seller_save');

        Route::get('/buyers', 'ProfileController@buyers')->name('buyers');
        Route::get('/buyers/search', 'ProfileController@buyersSearch')->name('buyers_search');
        Route::get('/buyers/{id}', 'ProfileController@buyerViewById')->name('buyer');
        Route::get('/buyers/{id}/edit', 'ProfileController@buyerEditById')->name('buyer_edit');
        Route::post('/buyers/{id}', 'ProfileController@buyerSaveById')->name('buyer_save');

        Route::post('/upload/temp', 'ResourcesController@uploadToTemp')->name('upload_temp');
        Route::get('/res/selling/{id}/{sub_path}', 'ResourcesController@getSellerSpecific')->where('sub_path', '.*');
        Route::get('/qrcode', 'ResourcesController@qrcode')->name('qrcode');
        Route::get('/res/avatar/empty', 'ResourcesController@emptyAvatar')->name('empty_avatar');

        Route::get('/admin/users', 'Admin\UsersController@users')->name('users');
        Route::get('/admin/users/search', 'Admin\UsersController@usersSearch')->name('users_search');
        Route::delete('/admin/users/{uid}', 'Admin\UsersController@delete')->name('user_delete');
        Route::patch('/admin/users/{uid}', 'Admin\UsersController@modify')->name('user_modify');
        Route::post('/admin/users/{uid}/email/verif', 'Admin\UsersController@sendVerifEmail')->name('user_modify');
        Route::patch('/admin/users/{uid}/profile', 'Admin\UsersController@modifyProfile')->name('user_modify_profile');
        Route::get('/admin/users/login/{user}', 'Admin\UsersController@login')->name('user_login');
    });

    
});