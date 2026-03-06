<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudo de Vistoria - #{{ $inspection->documento_numero ?? $inspection->id }}</title>
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
            break-inside: avoid;
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
        }
        
        .item-block:last-child {
            margin-bottom: 0;
        }
        
        .item-block-inner {
            /* Não usar page-break-inside aqui: evita espaço em branco entre detalhes e fotos no DomPDF */
        }
        
        .item-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        .item-num {
            display: inline-block;
            min-width: 3em;
            font-weight: bold;
            color: #333;
        }
        
        .item-line {
            font-size: 10pt;
            margin-bottom: 5px;
        }
        
        .item-line strong {
            color: #444;
            margin-right: 6px;
        }
        
        .item-dados {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin: 8px 0 12px 0;
        }
        
        .item-dados td {
            padding: 4px 10px 4px 0;
            vertical-align: top;
            border: none;
        }
        
        .item-dados td:first-child {
            width: 28%;
            font-weight: bold;
            color: #444;
        }
        
        .observacoes-box {
            margin-top: 10px;
            padding: 10px 12px;
            background: #fffde7;
            border-left: 4px solid #f9a825;
            font-size: 10pt;
        }
        
        .photos-group {
            margin-top: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fafafa;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .photos-group-title {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #444;
        }
        
        .photos-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .photos-table td {
            vertical-align: top;
            padding: 4px;
            width: 33.33%;
        }
        
        .photo-wrap {
            text-align: center;
            line-height: 0;
        }
        
        .photo {
            max-width: 100%;
            width: 180px;
            height: auto;
            border: 1px solid #ccc;
            display: block;
            margin: 0 auto 4px auto;
            vertical-align: top;
        }
        
        .imovel-titulo {
            font-size: 12pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 6px;
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

        .aviso-originais {
            font-size: 9pt;
            color: #555;
            margin-bottom: 20px;
            font-style: italic;
        }

        .photo-container {
            height: 200px;
            width: 100%;
            border: 1px solid #ccc;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            margin-bottom: 4px;
        }
        .photo-container img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .photo-caption {
            font-size: 8pt;
            color: #444;
            margin-top: 2px;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .photos-grid {
            width: 100%;
            margin-top: 8px;
        }
        .photos-row {
            margin-bottom: 8px;
        }
        .photo-cell-landscape {
            width: 100%;
            margin-bottom: 8px;
        }
        .photo-cell-portrait {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 8px;
        }
        .photos-table-cols {
            width: 100%;
            border-collapse: collapse;
        }
        .photos-table-cols td {
            width: 50%;
            vertical-align: top;
            padding: 4px;
        }
        .photos-table-cols td.photo-cell-full {
            width: 100%;
        }
        .declaracao-locatario {
            margin-top: 20px;
            padding: 12px 14px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-size: 10pt;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="doc-title">Laudo de Vistoria de Imóvel</div>
    <div class="doc-subtitle">Documento nº {{ $inspection->documento_numero ?? $inspection->id }} | Data da vistoria: {{ $inspection->data_vistoria->format('d/m/Y') }} às {{ $inspection->data_vistoria->format('H:i') }}</div>

    <p class="aviso-originais">As imagens originais permanecem armazenadas no servidor para eventuais consultas e comprovação.</p>
    
    <div class="section-title">1. Identificação do imóvel</div>
    
    @if($inspection->endereco)
    <div class="imovel-titulo">{{ $inspection->endereco }}</div>
    @endif
    <div class="endereco-box">
        @if($inspection->endereco_formatado)
            {{ $inspection->endereco_formatado }}
        @elseif($inspection->endereco_completo)
            {!! nl2br(e($inspection->endereco_completo)) !!}
        @elseif(!$inspection->endereco)
            <em>Endereço não informado.</em>
        @else
            {{ $inspection->endereco }}
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
        @php $itemsPdf = $inspection->items->where('is_draft', false); @endphp
        <div class="info-row">
            <div class="info-label">Total de itens vistoriados:</div>
            <div class="info-value">{{ $itemsPdf->count() }}</div>
        </div>
    </div>
    
    @if($itemsPdf->count() > 0)
        <div class="section-title">3. Resumo por categoria</div>
        <table class="resumo-table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsPdf->groupBy('categoria') as $categoria => $items)
                <tr>
                    <td>{{ $categoria ?: 'Sem categoria' }}</td>
                    <td>{{ $items->count() }} {{ $items->count() == 1 ? 'item' : 'itens' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="section-title">4. Itens vistoriados</div>
        
        @php
            $porLocalizacao = $itemsPdf->groupBy('localizacao');
            $ordemAmbientes = ['Sala', 'Cozinha', 'Quarto', 'Quarto casal', 'Quarto 2', 'Banheiro', 'Banheiro social', 'Banheiro suíte', 'Área de serviço', 'Varanda', 'Garagem'];
            $ambientesOrdenados = $porLocalizacao->sortBy(function ($_, $key) use ($ordemAmbientes) {
                $pos = array_search($key, $ordemAmbientes);
                return $pos === false ? 999 : $pos;
            });
        @endphp
        @foreach($ambientesOrdenados as $localizacao => $items)
            @php $ambienteNum = '4.' . $loop->iteration; @endphp
            <div class="category-block">
                <div class="category-heading">{{ $ambienteNum }} {{ $localizacao ?: 'Sem localização' }}</div>
                
                @foreach($items as $item)
                    @php $itemNum = $ambienteNum . '.' . $loop->iteration; @endphp
                    <div class="item-block">
                        <div class="item-block-inner">
                            <div class="item-name"><span class="item-num">{{ $itemNum }}</span> {!! html_entity_decode($item->item, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</div>
                            
                            <table class="item-dados">
                                @if($item->marca_modelo)
                                <tr><td>Marca/Modelo</td><td>{!! html_entity_decode($item->marca_modelo, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</td></tr>
                                @endif
                                <tr><td>Estado físico</td><td>{{ $item->estado_fisico }}</td></tr>
                                <tr><td>Funcionamento</td><td>{{ $item->funcionamento }}</td></tr>
                            </table>
                            
                            @if($item->observacoes)
                            <div class="observacoes-box">
                                <strong>Observações:</strong><br>
                                {!! html_entity_decode($item->observacoes, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}
                            </div>
                            @endif
                            
                            @php $itemPhotoRows = $photosForPdf[$item->id] ?? []; $totalItemPhotos = 0; foreach ($itemPhotoRows as $r) { $totalItemPhotos += count($r['entries']); } @endphp
                            @if(count($itemPhotoRows) > 0)
                            <div class="photos-group">
                                <div class="photos-group-title">Fotos ({{ $totalItemPhotos }})</div>
                                <table class="photos-table-cols" cellpadding="0" cellspacing="0">
                                    @foreach($itemPhotoRows as $row)
                                    <tr>
                                        @if($row['landscape'])
                                        <td colspan="2" class="photo-cell-full">
                                            @foreach($row['entries'] as $entry)
                                            <div class="photo-container"><img src="{{ public_path('storage/' . $entry['path']) }}" alt="Foto"></div>
                                            <div class="photo-caption">ID: {{ $entry['hash'] }} — Item: {!! html_entity_decode($entry['item_name'], ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</div>
                                            @endforeach
                                        </td>
                                        @else
                                        @foreach($row['entries'] as $entry)
                                        <td>
                                            <div class="photo-container"><img src="{{ public_path('storage/' . $entry['path']) }}" alt="Foto"></div>
                                            <div class="photo-caption">ID: {{ $entry['hash'] }} — Item: {!! html_entity_decode($entry['item_name'], ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</div>
                                        </td>
                                        @endforeach
                                        @if(count($row['entries']) === 1)
                                        <td></td>
                                        @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
    
    <div class="section-title">5. Declaração e assinaturas</div>

    <div class="declaracao-locatario">
        O locatário declara que vistoriou o imóvel, concorda com as condições descritas neste laudo e recebe o imóvel nas condições aqui registradas.
    </div>
    
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
        <p>Documento gerado em {{ $generatedAt->format('d/m/Y H:i:s') }} (horário de Brasília).</p>
        <p>As imagens originais permanecem armazenadas no servidor para eventuais consultas.</p>
        <p>Sistema de Vistoria de Imóveis</p>
    </div>
</body>
</html>
