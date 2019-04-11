<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellingProps extends Model
{
    protected $fillable = [
        'user_id'
    ];

    protected $appends = ['avatar', 'url'];

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

    public function getUrlAttribute()
    {
        return  route('seller', ['id' => $this->attributes['id']]);
    }

    public function getImagesAttribute($value)
    {
        $imgs_sending = array();
        $imgs_original = json_decode($value, true);
        if ($imgs_original && is_array($imgs_original)) {

            foreach ($imgs_original as $img) {
                $imgs_sending[] = [
                    'path' => $img,
                    'url' => getResourceUrl($this->attributes['id'], 'selling', ($img ? 'demos/' . $img : null)),
                ];
            }
        }

        return $imgs_sending;
    }

    public function getFilesAttribute($value)
    {
        $pdf_sending = array();
        $pdf_original = json_decode($value, true);
        if ($pdf_original && is_array($pdf_original)) {

            foreach ($pdf_original as $pdf) {
                $pdf_sending[] = [
                    'name' => $pdf['name'],
                    'path' => $pdf['path'],
                    'url' => getResourceUrl($this->attributes['id'], 'selling', 'pdfs/' . ($pdf['path'] ? $pdf['path'] : null)),
                ];
            }
        }
        
        return $pdf_sending;
    }

    public function getAvatarAttribute()
    {
        $url = route('empty_avatar') . '?i=' . $this->attributes['id'] . '&s=' . urlencode($this->attributes['name']);
        if (count($this->images) > 0) {
            $url = $this->images[0]['url'];
        };

        return $url;
    }

    public function scopePublished($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', '=', 2);
    }

    public function scopeDrafted($query)
    {
        return $query->where('status', '=', 0);
    }
    
}
