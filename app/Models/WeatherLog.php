<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    protected $fillable = [
    'city',
    'temperature',
    'condition'
];
}
