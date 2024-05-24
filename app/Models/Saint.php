<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saint extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'feast_day'];
}

