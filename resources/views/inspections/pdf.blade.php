<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudo de Vistoria - #{{ $inspection->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1a1a1a;
            padding: 40px 35px;
        }
        
        .doc-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 12px;
        }
        
        .doc-subtitle {
            text-align: center;
            font-size: 11pt;
            color: #444;
            margin-bottom: 28px;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 22px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #ccc;
        }
        
        .endereco-box {
            background: #f8f8f8;
            border: 1px solid #ddd;
            padding: 14px 18px;
            margin: 12px 0 20px 0;
            font-size: 11pt;
            line-height: 1.6;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 32%;
            padding: 6px 8px 6px 0;
            font-weight: bold;
            color: #333;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
        }
        
        .category-block {
            margin: 24px 0;
            page-break-inside: avoid;
        }
        
        .category-heading {
            background: #2c3e50;
            color: #fff;
            padding: 10px 14px;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 0;
        }
        
        .item-block {
            border: 1px solid #ddd;
            border-top: none;
            padding: 14px 14px 16px 14px;
            margin-bottom: 0;
            page-break-inside: avoid;
        }
        
        .item-block:last-child {
            margin-bottom: 0;
        }
        
        .item-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        
        .item-line {
            font-size: 10pt;
            margin-bottom: 5px;
        }
        
        .item-line strong {
            color: #444;
            margin-right: 6px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            margin: 4px 6px 4px 0;
            font-size: 9pt;
            border: 1px solid #999;
            background: #f5f5f5;
        }
        
        .observacoes-box {
            margin-top: 10px;
            padding: 10px 12px;
            background: #fffde7;
            border-left: 4px solid #f9a825;
            font-size: 10pt;
        }
        
        .photo {
            max-width: 280px;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
        
        .assinaturas {
            margin-top: 45px;
            padding-top: 30px;
            border-top: 2px solid #1a1a1a;
        }
        
        .assinaturas-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .assinatura-bloco {
            margin-top: 35px;
            width: 48%;
            display: inline-block;
            vertical-align: top;
            min-height: 100px;
        }
        
        .assinatura-bloco:first-child {
            margin-right: 2%;
        }
        
        .linha-assinatura {
            border-bottom: 1px solid #1a1a1a;
            height: 28px;
            margin-bottom: 6px;
            font-size: 10pt;
        }
        
        .linha-nome {
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
            margin-top: 4px;
        }
        
        .rodape {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #999;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        .resumo-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 20px 0;
            font-size: 10pt;
        }
        
        .resumo-table th,
        .resumo-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        
        .resumo-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="doc-title">Laudo de Vistoria de Imóvel</div>
    <div class="doc-subtitle">Documento nº {{ $inspection->id }} | Data da vistoria: {{ $inspection->data_vistoria->format('d/m/Y') }} às {{ $inspection->data_vistoria->format('H:i') }}</div>
    
    <div class="section-title">1. Identificação do imóvel</div>
    
    <div class="endereco-box">
        @if($inspection->endereco_formatado)
            {{ $inspection->endereco_formatado }}
        @elseif($inspection->endereco_completo)
            {!! nl2br(e($inspection->endereco_completo)) !!}
        @elseif($inspection->endereco)
            {{ $inspection->endereco }}
        @else
            <em>Endereço não informado.</em>
        @endif
    </div>
    
    <div class="section-title">2. Dados da vistoria</div>
    
    <div class="info-grid">
        @if($inspection->responsavel)
        <div class="info-row">
            <div class="info-label">Responsável pela vistoria:</div>
            <div class="info-value">{{ $inspection->responsavel }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Data e hora:</div>
            <div class="info-value">{{ $inspection->data_vistoria->format('d/m/Y \à\s H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total de itens vistoriados:</div>
            <div class="info-value">{{ $inspection->items->count() }}</div>
        </div>
    </div>
    
    @if($inspection->items->count() > 0)
        <div class="section-title">3. Resumo por categoria</div>
        <table class="resumo-table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspection->items->groupBy('categoria') as $categoria => $items)
                <tr>
                    <td>{{ $categoria ?: 'Sem categoria' }}</td>
                    <td>{{ $items->count() }} {{ $items->count() == 1 ? 'item' : 'itens' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="section-title">4. Itens vistoriados</div>
        
        @foreach($inspection->items->groupBy('localizacao') as $localizacao => $items)
            <div class="category-block">
                <div class="category-heading">{{ $localizacao ?: 'Sem localização' }}</div>
                
                @foreach($items as $item)
                    <div class="item-block">
                        <div class="item-name">{{ $item->item }}</div>
                        
                        @if($item->marca_modelo)
                        <div class="item-line"><strong>Marca/Modelo:</strong> {{ $item->marca_modelo }}</div>
                        @endif
                        
                        <div class="item-line"><strong>Estado físico:</strong> <span class="badge">{{ $item->estado_fisico }}</span></div>
                        <div class="item-line"><strong>Funcionamento:</strong> <span class="badge">{{ $item->funcionamento }}</span></div>
                        
                        @if($item->observacoes)
                        <div class="observacoes-box">
                            <strong>Observações:</strong><br>
                            {{ $item->observacoes }}
                        </div>
                        @endif
                        
                        @foreach($item->allPhotos() as $photoPath)
                        <div>
                            <img src="{{ public_path('storage/' . $photoPath) }}" class="photo" alt="Foto do item">
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
    
    <div class="section-title">5. Assinaturas</div>
    
    <div class="assinaturas">
        <div class="assinatura-bloco">
            <div class="linha-assinatura">{{ $inspection->responsavel ?: '_________________________________________' }}</div>
            <div class="linha-nome">Responsável pela vistoria</div>
        </div>
        <div class="assinatura-bloco">
            <div class="linha-assinatura">{{ $inspection->locatario_nome ?: '_________________________________________' }}</div>
            <div class="linha-nome">Locatário do imóvel</div>
        </div>
    </div>
    
    <div class="rodape">
        <p>Documento gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema de Vistoria de Imóveis</p>
    </div>
</body>
</html>
