<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function source()
    {
        return $this->belongsTo(\App\Models\Source::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
}
