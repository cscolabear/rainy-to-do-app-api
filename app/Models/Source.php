<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
}
