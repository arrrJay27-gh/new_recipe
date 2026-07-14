<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'instructions',
        'ingredients',
        'base_servings',
        'prep_time_minutes',
        'image_path',
    ];
}