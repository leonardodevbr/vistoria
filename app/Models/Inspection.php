<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    protected $fillable = [
        'endereco',
        'endereco_completo',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'responsavel',
        'locatario_nome',
        'data_vistoria'
    ];

    protected $casts = [
        'data_vistoria' => 'datetime'
    ];

    public function getCepFormatadoAttribute(): ?string
    {
        $cep = $this->cep ? preg_replace('/\D/', '', $this->cep) : '';
        if (strlen($cep) >= 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
        }
        return $cep ?: null;
    }

    public function getEnderecoFormatadoAttribute(): string
    {
        $cepLimpo = $this->cep ? preg_replace('/\D/', '', $this->cep) : '';
        $cepFormatado = strlen($cepLimpo) >= 8 ? substr($cepLimpo, 0, 5) . '-' . substr($cepLimpo, 5, 3) : $cepLimpo;
        $partes = array_filter([
            $this->logradouro,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->cidade ? ($this->cidade . ($this->uf ? ' - ' . $this->uf : '')) : $this->uf,
            $cepFormatado ? 'CEP ' . $cepFormatado : null,
        ]);
        return implode(', ', $partes) ?: '';
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }
}
