<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WeatherModel extends Model
{
    protected $table="weather";
    protected $primaryKey="id";
    public $timestamps=false;
}
