<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['name', 'icon', 'url', 'is_active', 'sort_order'];
}
