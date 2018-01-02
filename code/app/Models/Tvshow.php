<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tvshow extends Model
{
    /**
     * Get the seasons for the tv show.
     */
	public function seasons()
    {
        return $this->hasMany('App\Models\Season');
    }
}
