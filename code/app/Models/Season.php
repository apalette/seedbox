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
}
