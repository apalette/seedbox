<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
	/**
     * Get the user that owns the donwload.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	/**
     * Get the tv-show episode that owns the donwload.
     */
    public function episode()
    {
        return $this->hasOne('App\Models\Episode');
    }
	
	/**
     * Get the movie that owns the donwload.
     */
    public function movie()
    {
        return $this->hasOne('App\Models\Movie');
    }
}