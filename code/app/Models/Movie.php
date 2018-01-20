<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
	/**
     * Get the download record associated with the movie.
     */
    public function download()
    {
        return $this->belongsTo('App\Models\Download');
    }
}