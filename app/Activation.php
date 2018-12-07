<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    public $fillable = ['trial','original','code'];
}
