<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cellar_has_bottle extends Model
{
    use HasFactory;

	protected $fillable = ['cellier_id', 'bouteille_id'];
}
