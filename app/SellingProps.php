<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellingProps extends Model
{
    protected $fillable = [
        'user_id'
    ];

    //
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function socials()
    {
        $socials = $this->hasMany('App\Socials', 'ref_id', 'id');;
        $socials->where('ref_type', 1);

        return $socials;
    }

}
