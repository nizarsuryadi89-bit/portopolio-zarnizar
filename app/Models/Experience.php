<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['position', 'organization', 'description', 'start_date', 'end_date', 'is_current', 'sort_order'];
}
