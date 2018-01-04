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
}