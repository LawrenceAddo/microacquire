<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Profiles;
use App\Socials;
use App\SellingProps;

class ProfileBaseController extends Controller
{
    //
    public function __construct() {
       parent::__construct();
    }

    /**
     *
     * $for: 0: all, 1:published
     *
     */
    protected function querySellersSearch($data, $for = 0) {
        //
        $rev0 = getArrayVar($data, 'r0', '');
        $rev1 = getArrayVar($data, 'r1', '');
        $cost0 = getArrayVar($data, 'c0', '');
        $cost1 = getArrayVar($data, 'c1', '');
        $isFeatured = getArrayVar($data, 'f', 0); // ???
        $q = getArrayVar($data, 'q', '');

        $sellers = SellingProps::where('name', 'LIKE', '%' . $q . '%');
        if ($rev0 != '') {
            $sellers = $sellers->where('revenue', '>=', $rev0);
        }
        if ($rev1 != '') {
            $sellers = $sellers->where('revenue', '<=', $rev1);
        }
        if ($cost0 != '') {
            $sellers = $sellers->where('price', '>=', $cost0);
        }
        if ($cost1 != '') {
            $sellers = $sellers->where('price', '<=', $cost1);
        }

        if ($for == 1) {
            $sellers = $sellers->published();  
        }

        return $sellers;
    }
}
