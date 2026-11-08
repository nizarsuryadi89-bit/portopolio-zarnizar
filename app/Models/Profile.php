<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'tagline',
        'bio',
        'description',
        'photo',
        'email',
        'phone',
        'whatsapp',
        'location',
        'cv_file',
    ];
}
