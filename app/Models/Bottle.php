<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bottle extends Model
{
	use HasFactory;

	protected $fillable = ['name', 'price', 'image_url', 'country', 'volume', 'type', 'upc_saq'];

	//une bouteille peut être dans plusieurs cellier
	public function cellars()
	{
		return $this->belongsToMany(Cellar::class, 'cellar_has_bottles')
			->using(Cellar_Has_Bottle::class)
			->withPivot('quantity')
			->withTimestamps();
	}

	public function favoritedBy()
	{
    	return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
	}

}
