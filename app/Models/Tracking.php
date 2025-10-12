<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = [
        'device_name',
        'lat',
        'lng',
        'waktu',
    ];
}
