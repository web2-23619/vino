<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cellar extends Model
{
	use HasFactory;

	protected $fillable = ['name', 'quantity', 'user_id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	// un cellier a plusieurs bouteilles à travers la table pivot
	public function bottles()
	{
		return $this->belongsToMany(Bottle::class, 'cellar_has_bottles')
			->using(Cellar_has_bottle::class)
			->withPivot('quantity')
			->withTimestamps();
	}
}
