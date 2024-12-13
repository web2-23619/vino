<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bottle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'image_url', 'country', 'volume', 'type', 'upc_saq'];
}
