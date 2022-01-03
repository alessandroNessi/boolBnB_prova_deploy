<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistics';
    
    public function apartment()
    {
        return $this->belongsTo('App/Apartment');
    }
}
