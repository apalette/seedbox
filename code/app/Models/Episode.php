<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
	/**
     * Get the season that owns the episode.
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }
	
	 /**
     * Get the download record associated with the episode.
     */
    public function download()
    {
        return $this->belongsTo('App\Models\Download');
    }
}