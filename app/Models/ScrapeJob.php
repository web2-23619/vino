<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapeJob extends Model
{
    use HasFactory;

    // Champs qui peuvent être remplis
    protected $fillable = ['current_page', 'status'];

    // Pour ignorer les timestamps si vous n'en avez pas besoin
    public $timestamps = true;

    // Si vous voulez que le modèle retourne l'ID du job
    public function getJobId()
    {
        return $this->id;
    }
}
