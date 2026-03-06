<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    protected $fillable = [
        'documento_numero',
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
        'data_vistoria',
        'aprovado_em',
        'assinatura_hash',
        'pdf_path',
    ];

    protected $casts = [
        'data_vistoria' => 'datetime',
        'aprovado_em' => 'datetime',
    ];

    public function isAprovado(): bool
    {
        return $this->aprovado_em !== null;
    }

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

    /**
     * Conteúdo canônico para cálculo da assinatura digital (qualquer alteração altera o hash).
     */
    public function getConteudoParaAssinatura(): string
    {
        $this->load(['items.photos']);
        $parts = [
            'id' => $this->id,
            'documento_numero' => $this->documento_numero,
            'endereco' => $this->endereco,
            'endereco_completo' => $this->endereco_completo,
            'cep' => $this->cep,
            'logradouro' => $this->logradouro,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'responsavel' => $this->responsavel,
            'locatario_nome' => $this->locatario_nome,
            'data_vistoria' => $this->data_vistoria?->toIso8601String(),
        ];
        foreach ($this->items->where('is_draft', false)->sortBy('id') as $item) {
            $photos = $item->photos->sortBy('id')->pluck('path')->values()->all();
            $itemParts = [
                'item_id' => $item->id,
                'categoria' => $item->categoria,
                'item' => $item->item,
                'marca_modelo' => $item->marca_modelo,
                'localizacao' => $item->localizacao,
                'estado_fisico' => $item->estado_fisico,
                'funcionamento' => $item->funcionamento,
                'observacoes' => $item->observacoes,
                'foto' => $item->foto,
                'photos' => $photos,
            ];
            $parts['items'][] = $itemParts;
        }
        return json_encode($parts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
