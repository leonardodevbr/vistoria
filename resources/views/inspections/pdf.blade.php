<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vistoria de Imóvel - #{{ $inspection->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .info-section {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-section h2 {
            color: #667eea;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-item strong {
            color: #555;
        }
        
        .category-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .category-title {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        
        .item-card {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            page-break-inside: avoid;
        }
        
        .item-card h3 {
            color: #667eea;
            font-size: 13pt;
            margin-bottom: 10px;
        }
        
        .item-detail {
            margin-bottom: 6px;
            font-size: 10pt;
        }
        
        .item-detail strong {
            color: #555;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
            margin-right: 8px;
            margin-top: 5px;
        }
        
        .badge-estado {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-funcionamento {
            background: #d4edda;
            color: #155724;
        }
        
        .observacoes {
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-left: 3px solid #ffc107;
            font-size: 10pt;
        }
        
        .photo {
            max-width: 300px;
            margin-top: 10px;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #667eea;
            text-align: center;
            font-size: 9pt;
            color: #999;
        }
        
        .summary {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .summary h3 {
            color: #856404;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏠 LAUDO DE VISTORIA DE IMÓVEL</h1>
        <p style="font-size: 12pt; color: #666;">Vistoria #{{ $inspection->id }}</p>
    </div>
    
    <div class="info-section">
        <h2>📋 Informações Gerais</h2>
        
        @if($inspection->endereco)
        <div class="info-item">
            <strong>Endereço:</strong> {{ $inspection->endereco }}
        </div>
        @endif
        
        @if($inspection->responsavel)
        <div class="info-item">
            <strong>Responsável:</strong> {{ $inspection->responsavel }}
        </div>
        @endif
        
        <div class="info-item">
            <strong>Data da Vistoria:</strong> {{ $inspection->data_vistoria->format('d/m/Y') }} às {{ $inspection->data_vistoria->format('H:i') }}
        </div>
        
        <div class="info-item">
            <strong>Total de Itens:</strong> {{ $inspection->items->count() }}
        </div>
    </div>
    
    @if($inspection->items->count() > 0)
        <div class="summary">
            <h3>📊 Resumo por Categoria</h3>
            @foreach($inspection->items->groupBy('categoria') as $categoria => $items)
                <div class="info-item">
                    <strong>{{ $categoria ?: 'Sem Categoria' }}:</strong> {{ $items->count() }} {{ $items->count() == 1 ? 'item' : 'itens' }}
                </div>
            @endforeach
        </div>
        
        @foreach($inspection->items->groupBy('categoria') as $categoria => $items)
            <div class="category-section">
                <div class="category-title">
                    {{ strtoupper($categoria ?: 'SEM CATEGORIA') }}
                </div>
                
                @foreach($items as $item)
                    <div class="item-card">
                        <h3>{{ $item->item }}</h3>
                        
                        @if($item->marca_modelo)
                        <div class="item-detail">
                            <strong>Marca/Modelo:</strong> {{ $item->marca_modelo }}
                        </div>
                        @endif
                        
                        <div class="item-detail">
                            <strong>Localização:</strong> {{ $item->localizacao }}
                        </div>
                        
                        <div style="margin-top: 8px;">
                            <span class="badge badge-estado">{{ $item->estado_fisico }}</span>
                            <span class="badge badge-funcionamento">{{ $item->funcionamento }}</span>
                        </div>
                        
                        @if($item->observacoes)
                        <div class="observacoes">
                            <strong>Observações:</strong><br>
                            {{ $item->observacoes }}
                        </div>
                        @endif
                        
                        @if($item->foto)
                        <div>
                            <img src="{{ public_path('storage/' . $item->foto) }}" 
                                 class="photo" 
                                 alt="Foto do item">
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
    
    <div class="footer">
        <p>Documento gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema de Vistoria de Imóveis</p>
    </div>
</body>
</html>
