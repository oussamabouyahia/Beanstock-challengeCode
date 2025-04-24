<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogementEncadrement extends Model
{
    protected $table = 'logement_encadrements';
    protected $fillable = [
        'geographic_sector',
        'street_number',
        'street_name',
        'room_number',
        'construction_period',
        'furnished_type',
        'reference',
        'major_reference',
        'minor_reference',
        'year',
        'city',
        'INSEE_code',
        'geographic_shape',
        'geographic_point_2d'
    ];
    public function quartier(): BelongsTo
    {
        return $this->belongsTo(QuartiersParis::class, 'quartier_id');
    }
}
