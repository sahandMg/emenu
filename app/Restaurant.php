<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public $fillable = ['name','payMethod','image','logo','address','tel','tableCounting'];
}
