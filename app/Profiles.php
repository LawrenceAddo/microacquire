<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    protected $fillable = [
        'user_id'
    ];

    //
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function allSocials()
    {
        $socials = $this->hasMany('App\Socials', 'ref_id', 'user_id');;
        $socials->where('ref_type', 0);

        return $socials;
    }

    public function socials()
    {
        $socials = $this->hasMany('App\Socials', 'ref_id', 'user_id');
        $socials->where([
                ['ref_type', '=', 0],
                ['status', '=', 1],
            ])
            ->select(['social_type', 'social_url'])
            ->orderBy('seq', 'DESC');

        return $socials;
    }
}
