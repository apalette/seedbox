<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
	/**
     * Get the episodes for the season.
     */
	public function episodes()
    {
        return $this->hasMany('App\Models\Episode');
    }
	
	/**
     * Get the tvshow that owns the season.
     */
    public function tvshow()
    {
        return $this->belongsTo('App\Models\Tvshow');
    }
}
