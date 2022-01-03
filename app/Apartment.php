<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable=["user_id", "title", "rooms", "guests_number", "bathrooms", "sqm", "number", "description"];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    public function services()
    {
        return $this->belongsToMany('App\Service');
    }
    public function sponsorships()
    {
        return $this->belongsToMany('App\Sponsorship')->withPivot('created_at');
    }
    public function images()
    {
        // return 'stocazzo';
        return $this->hasMany('App\Image');
    }
    public function statistics()
    {
        return $this->hasMany('App\Statistic');
    }
}
