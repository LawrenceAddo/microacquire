<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController  extends Controller
{
    //

    /**
     * Show the home page
     *
     * @param  
     * @return View
     */
    public function home()
    {
        return view('pages.home', ['page_class' => 'home']);
    }


    public function seller()
    {
        return view('biz.seller.profile', ['page_class' => 'seller-profile']);
    }

    public function edit()
    {
        return view('biz.seller.profile', ['page_class' => 'seller-profile']);
    }
}
