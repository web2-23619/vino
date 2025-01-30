<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteBottle extends Model
{
    protected $fillable = ['user_id', 'bottle_id'];

    // Indique que cette table n'a pas de table associée
    public $timestamps = false;
}
