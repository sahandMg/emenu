<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public $fillable = ['valid','sold','name','price','image','description','category'];

    public function category(){

        return $this->hasOne(Category::class);
    }
}
