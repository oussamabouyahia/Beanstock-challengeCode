<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuartiersParis extends Model
{
    protected $table = 'quartiers_paris';
    //this step not needed for the moment
    protected $fillable = [
        'N_SQ_QU',
        'street_number',
        'C_QUINSEE',
        'L_QU',
        'C_AR',
        'N_SQ_AR',
        'perimetre',
        'surface',
        'geometry_X_Y',
        'zip_code'
    ];
    public function LogementEncadrements(): HasMany
    {
        return $this->hasMany(LogementEncadrement::class, 'quartier_id');
    }
}
