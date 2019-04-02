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

}
