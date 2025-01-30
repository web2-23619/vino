<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'username',
        'email',
        'password',
		'isAdmin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cellars()
    {
        return $this->hasMany(Cellar::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

public function favorites()
{
    return $this->belongsToMany(Bottle::class, 'favorites', 'user_id', 'bottle_id');
}
    // Récupérer toutes les bouteilles favorites d'un utilisateur
    public function favoriteBottles()
    {
        return $this->hasMany(FavoriteBottle::class, 'user_id');
    }

    // Vérifier si une bouteille est favorite pour un utilisateur donné
    public function isFavorite($bottleId)
    {
        return $this->favorites()->where('bottle_id', $bottleId)->exists();
    }

    // Ajouter ou supprimer une bouteille des favoris
    public function toggleFavorite($bottleId)
    {
        $favorite = $this->favoriteBottles()->where('bottle_id', $bottleId)->first();

        if ($favorite) {
            // Supprimer du tableau des favoris
            $favorite->delete();
        } else {
            // Ajouter aux favoris
            $this->favoriteBottles()->create(['bottle_id' => $bottleId]);
        }
    }

}
