<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socials extends Model
{
    //
    protected $fillable = [
        'ref_id', 'ref_type', 'social_type'
    ];
}
