<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'url',
        'date',
        'is_active',
        'sort_order',
    ];
}
