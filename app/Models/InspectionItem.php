<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionItem extends Model
{
    protected $fillable = [
        'inspection_id',
        'categoria',
        'item',
        'marca_modelo',
        'localizacao',
        'estado_fisico',
        'funcionamento',
        'observacoes',
        'foto'
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionItemPhoto::class);
    }

    public function allPhotos(): \Illuminate\Support\Collection
    {
        $paths = collect();
        if ($this->foto) {
            $paths->push($this->foto);
        }
        $paths = $paths->merge($this->photos->pluck('path'));
        return $paths->unique()->values();
    }
}
