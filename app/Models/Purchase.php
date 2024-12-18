<?php

namespace App\Models;

use App\Models\Bottle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;


	protected $fillable = [
		'user_id',
		'bottle_id',
		'quantity',
	];

	public function bottle()
	{
		return $this->belongsTo(Bottle::class);
	}
}
