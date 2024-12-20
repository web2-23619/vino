<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Cellar_has_bottle extends Pivot
{
    use HasFactory;

	protected $fillable = ['cellier_id', 'bouteille_id', 'quantity'];
}
