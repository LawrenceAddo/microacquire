<?php

namespace App\Http\Controllers;

use View;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        //
        View::share ( '_authMenus', [
            ['path' => 'sellers', 'slug' => 'sellers', 'label' => 'Company Listing', 'place'=>'top,footer'],
            ['path' => 'profile', 'slug' => 'profile', 'label' => 'Profile', 'place'=>'top,footer'],
            ['path' => 'support', 'slug' => 'support', 'label' => 'Support', 'place'=>'top,footer'],
            ['path' => 'logout', 'slug' => 'Logout', 'label' => 'Logout', 'place'=>'top,footer'],
            ['path' => 'settings', 'slug' => 'settings', 'label' => 'Hi %%name%%', 'place'=>'top'],
        ]);
        View::share ( '_guestMenus', [
            ['path' => 'apply', 'slug' => 'apply', 'label' => 'Apply', 'place'=>'top,footer'],
            ['path' => 'about', 'slug' => 'about', 'label' => 'About', 'place'=>'top,footer'],
            ['path' => 'contact', 'slug' => 'contact', 'label' => 'Contact', 'place'=>'top,footer'],
            ['path' => 'login', 'slug' => 'Logout', 'label' => 'Login', 'place'=>'top,footer'],
        ]);
    }
}
