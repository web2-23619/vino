<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bouteille extends Model
{
    use HasFactory;

    protected $table = 'bouteille';

    protected $fillable = ['nom', 'prix', 'image', 'pays', 'volume', 'type', 'upc_saq', 'updated_at', 'created_at'];
}
