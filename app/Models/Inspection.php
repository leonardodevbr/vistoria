<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    protected $fillable = [
        'endereco',
        'responsavel',
        'data_vistoria'
    ];

    protected $casts = [
        'data_vistoria' => 'datetime'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }
}
