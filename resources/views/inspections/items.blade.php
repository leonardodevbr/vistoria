@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-primary btn-sm btn-icon"><i data-lucide="pencil" width="16" height="16"></i> Editar dados da vistoria</a>
        <a href="{{ route('inspections.index') }}" class="btn btn-outline btn-sm btn-icon"><i data-lucide="arrow-left" width="16" height="16"></i> Voltar à lista</a>
    </div>
    
    <h2 class="icon-wrap" style="margin-bottom: 0.5rem;"><i data-lucide="clipboard-list" width="22" height="22"></i> Vistoria #{{ $inspection->id }}</h2>
    @if($inspection->endereco)
        <p style="color: #666; margin-bottom: 1rem;">{{ $inspection->endereco }}</p>
    @endif
</div>

<div id="permissionsModal" class="permissions-modal" style="display: none;">
    <div class="permissions-modal-overlay"></div>
    <div class="permissions-modal-box">
        <h3 class="icon-wrap" style="margin-bottom: 0.75rem;"><i data-lucide="shield-alert" width="22" height="22"></i> Permissões necessárias</h3>
        <p style="color: #444; font-size: 0.95rem; margin-bottom: 0.75rem; line-height: 1.5;">
            Para validar o laudo da vistoria, o sistema precisa de acesso à <strong>localização do dispositivo</strong> e à <strong>câmera</strong>. Esses dados compõem o laudo e garantem a autenticidade da vistoria perante o locatário.
        </p>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.25rem;">
            Está faltando pelo menos uma permissão. Clique em &quot;Dar permissão&quot; para que o navegador solicite o acesso.
        </p>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
            <button type="button" id="btnGrantPermission" class="btn btn-success btn-icon">
                <i data-lucide="check-circle" width="18" height="18"></i> Dar permissão
            </button>
            <a href="{{ route('inspections.index') }}" class="btn btn-outline btn-icon">
                <i data-lucide="arrow-left" width="18" height="18"></i> Voltar ao início
            </a>
        </div>
        <p id="permissionsModalStatus" style="font-size: 0.85rem; color: #666; margin-top: 0.75rem; margin-bottom: 0;"></p>
    </div>
</div>

<div class="card">
    <h3 class="icon-wrap" style="margin-bottom: 1rem;"><i data-lucide="layout-grid" width="20" height="20"></i> Adicionar itens por ambiente</h3>
    <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Selecione o ambiente e adicione todos os itens desse ambiente. Depois altere o ambiente para cadastrar itens de outro cômodo.</p>
    
    <div class="form-group">
        <label class="form-label icon-wrap"><i data-lucide="door-open" width="14" height="14"></i> Ambiente atual *</label>
        <select id="ambienteAtual" class="form-control" required autocomplete="off">
            <option value="">Selecione o ambiente...</option>
            <optgroup label="Áreas sociais">
                <option value="Sala">Sala</option>
                <option value="Cozinha">Cozinha</option>
            </optgroup>
            <optgroup label="Quartos">
                <option value="Quarto">Quarto</option>
                <option value="Quarto casal">Quarto casal</option>
                <option value="Quarto 2">Quarto 2</option>
            </optgroup>
            <optgroup label="Banheiros">
                <option value="Banheiro">Banheiro</option>
                <option value="Banheiro social">Banheiro social</option>
                <option value="Banheiro suíte">Banheiro suíte</option>
            </optgroup>
            <optgroup label="Outros">
                <option value="Área de serviço">Área de serviço</option>
                <option value="Varanda">Varanda</option>
                <option value="Garagem">Garagem</option>
            </optgroup>
        </select>
    </div>
    
    <form id="itemForm" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <input type="hidden" name="localizacao" id="localizacaoHidden" value="">
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="folder" width="14" height="14"></i> Categoria</label>
            <select id="categoria" name="categoria" class="form-control">
                <option value="">Selecione ou digite...</option>
                <option value="Eletrodomésticos">Eletrodomésticos</option>
                <option value="Eletrônicos">Eletrônicos</option>
                <option value="Móveis">Móveis</option>
                <option value="Planejados">Planejados</option>
                <option value="Piso">Piso</option>
                <option value="Revestimento">Revestimento</option>
                <option value="Louças e metais">Louças e metais</option>
                <option value="Área de Serviço">Área de Serviço</option>
                <option value="Outros">Outros</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="tag" width="14" height="14"></i> Item *</label>
            <input type="text" id="item" name="item" class="form-control" required autocomplete="off"
                   placeholder="Ex: Geladeira, Sofá, Armário...">
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="package" width="14" height="14"></i> Marca/Modelo</label>
            <select id="marca_modelo" name="marca_modelo" class="form-control">
                <option value="">Selecione ou digite...</option>
                <option value="Não se aplica">Não se aplica</option>
                <optgroup label="A">
                    <option value="AOC">AOC</option>
                    <option value="Arno">Arno</option>
                    <option value="Atlas">Atlas</option>
                </optgroup>
                <optgroup label="B">
                    <option value="Black+Decker">Black+Decker</option>
                    <option value="Bosch">Bosch</option>
                    <option value="Brastemp">Brastemp</option>
                    <option value="Britânia">Britânia</option>
                </optgroup>
                <optgroup label="C-D">
                    <option value="Consul">Consul</option>
                    <option value="Dako">Dako</option>
                    <option value="Daikin">Daikin</option>
                    <option value="Dolce Gusto">Dolce Gusto</option>
                    <option value="Dyson">Dyson</option>
                </optgroup>
                <optgroup label="E-K">
                    <option value="Electrolux">Electrolux</option>
                    <option value="Fischer">Fischer</option>
                    <option value="Gree">Gree</option>
                    <option value="Hisense">Hisense</option>
                    <option value="Karcher">Karcher</option>
                </optgroup>
                <optgroup label="L-P">
                    <option value="LG">LG</option>
                    <option value="Midea">Midea</option>
                    <option value="Mondial">Mondial</option>
                    <option value="Mueller">Mueller</option>
                    <option value="Nespresso">Nespresso</option>
                    <option value="Oster">Oster</option>
                    <option value="Panasonic">Panasonic</option>
                    <option value="Philco">Philco</option>
                    <option value="Philips Walita">Philips Walita</option>
                </optgroup>
                <optgroup label="S-Z">
                    <option value="Samsung">Samsung</option>
                    <option value="Sony">Sony</option>
                    <option value="Springer Carrier">Springer Carrier</option>
                    <option value="Suggar">Suggar</option>
                    <option value="TCL">TCL</option>
                    <option value="Tramontina">Tramontina</option>
                </optgroup>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="star" width="14" height="14"></i> Estado físico *</label>
            <select id="estado_fisico" name="estado_fisico" class="form-control" required>
                <option value="">Selecione...</option>
                <option value="Novo">Novo</option>
                <option value="Seminovo">Seminovo</option>
                <option value="Ótimo">Ótimo</option>
                <option value="Bom">Bom</option>
                <option value="Regular">Regular</option>
                <option value="Não se aplica">Não se aplica</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="settings" width="14" height="14"></i> Funcionamento *</label>
            <select id="funcionamento" name="funcionamento" class="form-control" required>
                <option value="">Selecione...</option>
                <option value="Funcionando perfeitamente">Funcionando perfeitamente</option>
                <option value="Funcionando">Funcionando</option>
                <option value="Funcionando com ressalvas">Funcionando com ressalvas</option>
                <option value="Não testado">Não testado</option>
                <option value="Não funciona">Não funciona</option>
                <option value="Não se aplica">Não se aplica</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="message-square" width="14" height="14"></i> Observações</label>
            <textarea name="observacoes" class="form-control" autocomplete="off"
                      placeholder="Detalhe riscos, manchas, folgas, etc."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="camera" width="14" height="14"></i> Fotos</label>
            <input type="file" id="fotosInput" accept="image/*" multiple style="display: none;">
            <input type="file" id="cameraInput" accept="image/*" capture="environment" style="display: none;">
            <div class="foto-actions">
                <button type="button" id="btnGaleria" class="btn btn-primary btn-sm btn-icon">
                    <i data-lucide="image" width="16" height="16"></i> Galeria
                </button>
                <button type="button" id="btnCamera" class="btn btn-primary btn-sm btn-icon">
                    <i data-lucide="camera" width="16" height="16"></i> Câmera
                </button>
            </div>
            <div id="photoPreviewList" class="photo-preview-list"></div>
        </div>
        <div id="cameraModal" class="camera-modal" style="display: none;">
            <div class="camera-modal-content">
                <div id="cameraViewport" class="camera-viewport">
                    <div id="cameraLoading" class="camera-loading">Abrindo câmera...</div>
                    <div id="cameraLiveWrap" class="camera-live-wrap">
                        <video id="cameraVideo" autoplay playsinline muted></video>
                    </div>
                    <div id="cameraPreviewWrap" class="camera-preview-wrap" style="display: none;">
                        <img id="cameraPreviewImg" src="" alt="Preview da foto">
                    </div>
                </div>
                <div id="cameraActionsLive" class="camera-modal-actions">
                    <button type="button" id="btnCapture" class="btn btn-success" disabled>Capturar</button>
                    <button type="button" id="btnCloseCamera" class="btn btn-primary">Fechar</button>
                </div>
                <div id="cameraActionsPreview" class="camera-modal-actions" style="display: none;">
                    <button type="button" id="btnAddMorePhoto" class="btn btn-primary btn-icon"><i data-lucide="plus-circle" width="18" height="18"></i> Adicionar mais</button>
                    <button type="button" id="btnUsePhotos" class="btn btn-success btn-icon"><i data-lucide="check" width="18" height="18"></i> Concluir</button>
                </div>
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-success btn-icon" id="btnAddItem">
                <i data-lucide="plus-circle" width="18" height="18"></i> Adicionar item
            </button>
            <span id="addItemAutosaveStatus" class="add-item-autosave-status" aria-live="polite"></span>
        </div>
    </form>
</div>

<div id="itemsList">
    @php $itemsListados = $inspection->items->filter(fn($i) => $i->is_draft !== true); @endphp
    @if($itemsListados->count() > 0)
        <div class="card">
            <h3 class="icon-wrap" style="margin-bottom: 1rem;"><i data-lucide="list-checks" width="20" height="20"></i> Itens cadastrados ({{ $itemsListados->count() }})</h3>
            
            @foreach($itemsListados->groupBy('localizacao') as $localizacao => $items)
                <h4 style="color: #1e40af; margin-top: 1.5rem; margin-bottom: 1rem;">
                    {{ $localizacao ?: 'Sem localização' }}
                </h4>
                
                @foreach($items as $item)
                    <div class="item-card" 
                         data-item-id="{{ $item->id }}"
                         data-categoria="{{ e($item->categoria) }}"
                         data-item="{{ e($item->item) }}"
                         data-marca-modelo="{{ e($item->marca_modelo) }}"
                         data-localizacao="{{ e($item->localizacao) }}"
                         data-estado-fisico="{{ e($item->estado_fisico) }}"
                         data-funcionamento="{{ e($item->funcionamento) }}"
                         data-observacoes="{{ e($item->observacoes) }}"
                         data-photos='@json($item->photos->map(fn($p) => ["id" => $p->id, "path" => $p->path, "url" => asset("storage/" . $p->path)])->values())'
                         data-legacy-foto="{{ $item->photos->count() === 0 ? e($item->foto) : '' }}">
                        <div class="item-card-actions">
                            <button type="button" class="edit-btn" onclick="openEditItem(this)" title="Editar">
                                <i data-lucide="pencil" width="14" height="14"></i>
                            </button>
                            <button type="button" class="delete-btn" onclick="deleteItem({{ $item->id }})" title="Excluir">×</button>
                        </div>
                        
                        <h4>{{ $item->item }}</h4>
                        
                        @if($item->marca_modelo)
                            <p class="item-info">
                                <strong>Marca/Modelo:</strong> {{ $item->marca_modelo }}
                            </p>
                        @endif
                        
                        <p class="item-info">
                            <strong>Localização:</strong> {{ $item->localizacao }}
                        </p>
                        
                        <div style="margin: 0.5rem 0;">
                            <span class="badge badge-info">{{ $item->estado_fisico }}</span>
                            <span class="badge badge-success">{{ $item->funcionamento }}</span>
                        </div>
                        
                        @if($item->observacoes)
                            <p class="item-info" style="margin-top: 0.5rem;">
                                <strong>Observações:</strong> {{ $item->observacoes }}
                            </p>
                        @endif
                        
                        @php $itemPhotos = $item->allPhotos(); @endphp
                        @if($itemPhotos->isNotEmpty())
                            <div class="item-gallery-carousel" data-total="{{ $itemPhotos->count() }}">
                                <div class="carousel-viewport carousel-viewport-clickable" title="Clique para tela cheia">
                                    <span class="carousel-fullscreen-hint" aria-hidden="true"><i data-lucide="maximize" width="20" height="20"></i></span>
                                    @foreach($itemPhotos as $index => $photoPath)
                                        <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                            <img src="{{ asset('storage/' . $photoPath) }}" alt="Foto {{ $index + 1 }} do item" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="carousel-controls">
                                    <button type="button" class="carousel-btn carousel-prev" aria-label="Anterior"><i data-lucide="chevron-left" width="20" height="20"></i></button>
                                    <span class="carousel-counter"><span class="carousel-current">1</span> / {{ $itemPhotos->count() }}</span>
                                    <button type="button" class="carousel-btn carousel-next" aria-label="Próximo"><i data-lucide="chevron-right" width="20" height="20"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
        
        <a href="{{ route('inspections.pdf', $inspection) }}" class="btn btn-success btn-icon">
            <i data-lucide="file-down" width="18" height="18"></i> Gerar PDF da vistoria
        </a>
    @else
        <div class="card" style="text-align: center; padding: 2rem;">
            <p style="color: #999;">Nenhum item cadastrado ainda. Selecione um ambiente acima e adicione os itens.</p>
        </div>
    @endif
</div>

<div id="editItemModal" class="edit-modal" style="display: none;">
    <div class="edit-modal-backdrop" onclick="closeEditItem()"></div>
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h3 class="icon-wrap"><i data-lucide="pencil" width="20" height="20"></i> Editar item</h3>
            <button type="button" class="edit-modal-close" onclick="closeEditItem()">×</button>
        </div>
        <form id="editItemForm" enctype="multipart/form-data" class="edit-modal-form" autocomplete="off">
            <input type="hidden" name="_method" value="PUT">
            @csrf
            <div class="edit-modal-body">
            <div class="form-group">
                <label class="form-label">Ambiente *</label>
                <select id="editLocalizacao" name="localizacao" class="form-control" required>
                    <option value="">Selecione o ambiente...</option>
                    <optgroup label="Áreas sociais">
                        <option value="Sala">Sala</option>
                        <option value="Cozinha">Cozinha</option>
                    </optgroup>
                    <optgroup label="Quartos">
                        <option value="Quarto">Quarto</option>
                        <option value="Quarto casal">Quarto casal</option>
                        <option value="Quarto 2">Quarto 2</option>
                    </optgroup>
                    <optgroup label="Banheiros">
                        <option value="Banheiro">Banheiro</option>
                        <option value="Banheiro social">Banheiro social</option>
                        <option value="Banheiro suíte">Banheiro suíte</option>
                    </optgroup>
                    <optgroup label="Outros">
                        <option value="Área de serviço">Área de serviço</option>
                        <option value="Varanda">Varanda</option>
                        <option value="Garagem">Garagem</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Categoria</label>
                <select id="editCategoria" name="categoria" class="form-control">
                    <option value="">Selecione ou digite...</option>
                    <option value="Eletrodomésticos">Eletrodomésticos</option>
                    <option value="Eletrônicos">Eletrônicos</option>
                    <option value="Móveis">Móveis</option>
                    <option value="Planejados">Planejados</option>
                    <option value="Piso">Piso</option>
                    <option value="Revestimento">Revestimento</option>
                    <option value="Louças e metais">Louças e metais</option>
                    <option value="Área de Serviço">Área de Serviço</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Item *</label>
                <input type="text" id="editItem" name="item" class="form-control" required autocomplete="off" placeholder="Ex: Geladeira, Sofá...">
            </div>
            <div class="form-group">
                <label class="form-label">Marca/Modelo</label>
                <select id="editMarcaModelo" name="marca_modelo" class="form-control">
                    <option value="">Selecione ou digite...</option>
                    <option value="Não se aplica">Não se aplica</option>
                    <optgroup label="A">
                        <option value="AOC">AOC</option>
                        <option value="Arno">Arno</option>
                        <option value="Atlas">Atlas</option>
                    </optgroup>
                    <optgroup label="B">
                        <option value="Black+Decker">Black+Decker</option>
                        <option value="Bosch">Bosch</option>
                        <option value="Brastemp">Brastemp</option>
                        <option value="Britânia">Britânia</option>
                    </optgroup>
                    <optgroup label="C-D">
                        <option value="Consul">Consul</option>
                        <option value="Dako">Dako</option>
                        <option value="Daikin">Daikin</option>
                        <option value="Dolce Gusto">Dolce Gusto</option>
                        <option value="Dyson">Dyson</option>
                    </optgroup>
                    <optgroup label="E-K">
                        <option value="Electrolux">Electrolux</option>
                        <option value="Fischer">Fischer</option>
                        <option value="Gree">Gree</option>
                        <option value="Hisense">Hisense</option>
                        <option value="Karcher">Karcher</option>
                    </optgroup>
                    <optgroup label="L-P">
                        <option value="LG">LG</option>
                        <option value="Midea">Midea</option>
                        <option value="Mondial">Mondial</option>
                        <option value="Mueller">Mueller</option>
                        <option value="Nespresso">Nespresso</option>
                        <option value="Oster">Oster</option>
                        <option value="Panasonic">Panasonic</option>
                        <option value="Philco">Philco</option>
                        <option value="Philips Walita">Philips Walita</option>
                    </optgroup>
                    <optgroup label="S-Z">
                        <option value="Samsung">Samsung</option>
                        <option value="Sony">Sony</option>
                        <option value="Springer Carrier">Springer Carrier</option>
                        <option value="Suggar">Suggar</option>
                        <option value="TCL">TCL</option>
                        <option value="Tramontina">Tramontina</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Estado físico *</label>
                <select id="editEstadoFisico" name="estado_fisico" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="Novo">Novo</option>
                    <option value="Seminovo">Seminovo</option>
                    <option value="Ótimo">Ótimo</option>
                    <option value="Bom">Bom</option>
                    <option value="Regular">Regular</option>
                    <option value="Não se aplica">Não se aplica</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Funcionamento *</label>
                <select id="editFuncionamento" name="funcionamento" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="Funcionando perfeitamente">Funcionando perfeitamente</option>
                    <option value="Funcionando">Funcionando</option>
                    <option value="Funcionando com ressalvas">Funcionando com ressalvas</option>
                    <option value="Não testado">Não testado</option>
                    <option value="Não funciona">Não funciona</option>
                    <option value="Não se aplica">Não se aplica</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Observações</label>
                <textarea id="editObservacoes" name="observacoes" class="form-control" autocomplete="off" placeholder="Detalhe riscos, manchas, etc."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Adicionar mais fotos (opcional)</label>
                <input type="file" id="editFotosInput" accept="image/*" multiple style="display: none;">
                <input type="file" id="editCameraInput" accept="image/*" capture="environment" style="display: none;">
                <div class="foto-actions">
                    <button type="button" id="editBtnGaleria" class="btn btn-outline btn-sm btn-icon"><i data-lucide="image" width="16" height="16"></i> Galeria</button>
                    <button type="button" id="editBtnCamera" class="btn btn-outline btn-sm btn-icon"><i data-lucide="camera" width="16" height="16"></i> Câmera</button>
                </div>
                <div id="editPhotoPreviewList" class="photo-preview-list"></div>
            </div>
            </div>
            <div class="edit-modal-footer">
                <span id="editAutosaveStatus" class="edit-autosave-status" aria-live="polite"></span>
                <button type="button" class="btn btn-cancel" onclick="closeEditItem()">Cancelar</button>
                <button type="submit" class="btn btn-success btn-icon" id="btnSaveEditItem">
                    <i data-lucide="check" width="16" height="16"></i> Salvar alterações
                </button>
            </div>
        </form>
    </div>
</div>

<div id="carouselFullscreenOverlay" class="carousel-fullscreen-overlay" aria-hidden="true" onclick="if(event.target===this) closeCarouselFullscreen();">
    <button type="button" class="carousel-fullscreen-close" onclick="closeCarouselFullscreen()" aria-label="Fechar">×</button>
    <div class="carousel-fullscreen-content">
        <img id="carouselFullscreenImg" src="" alt="Foto em tela cheia">
    </div>
    <div class="carousel-fullscreen-controls">
        <button type="button" class="carousel-btn carousel-prev" onclick="fullscreenPrev()" aria-label="Anterior"><i data-lucide="chevron-left" width="24" height="24"></i></button>
        <span class="carousel-counter"><span id="carouselFullscreenCounter">1</span> / <span id="carouselFullscreenTotal">1</span></span>
        <button type="button" class="carousel-btn carousel-next" onclick="fullscreenNext()" aria-label="Próximo"><i data-lucide="chevron-right" width="24" height="24"></i></button>
    </div>
</div>

@push('styles')
<style>
/* Tom Select: um único campo visível (sem caixa dentro de caixa) e dropdown com busca */
.ts-wrapper.single .ts-control { background: #fff; }
.ts-wrapper { border: none !important; background: transparent !important; box-shadow: none !important; padding: 0 !important; }
.ts-wrapper .ts-control { border: 2px solid #e0e0e0 !important; border-radius: 8px !important; min-height: 42px; }
.ts-wrapper .ts-control:focus-within { border-color: #2563eb !important; outline: none !important; }
.ts-dropdown { margin-top: 6px !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important; z-index: 10050 !important; }
.foto-actions { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; }
.photo-preview-list { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
.photo-preview-item { position: relative; width: 80px; height: 80px; flex-shrink: 0; }
.photo-preview-thumb { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
.photo-remove { position: absolute; top: 2px; right: 2px; width: 22px; height: 22px; border: none; border-radius: 50%; background: #f45c43; color: white; cursor: pointer; font-size: 1rem; line-height: 1; padding: 0; display: flex; align-items: center; justify-content: center; }
.camera-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 9999; display: flex; align-items: stretch; justify-content: center; padding: 0; }
.camera-modal-content { background: #000; overflow: hidden; width: 100%; max-width: 100%; min-height: 100%; display: flex; flex-direction: column; position: relative; }
.camera-viewport { width: 100%; flex: 1; min-height: 60vh; max-height: calc(100vh - 80px); background: #000; position: relative; flex-shrink: 0; }
.camera-loading { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #fff; padding: 1rem; text-align: center; z-index: 2; }
.camera-live-wrap { position: absolute; inset: 0; display: none; }
.camera-live-wrap.visible { display: block; }
.camera-live-wrap video { display: block; width: 100%; height: 100%; object-fit: cover; background: #000; }
.camera-preview-wrap { position: absolute; inset: 0; display: none; flex-direction: column; }
.camera-preview-wrap.visible { display: flex; }
.camera-preview-wrap img { display: block; width: 100%; height: 100%; object-fit: contain; background: #000; }
.camera-modal-actions { display: flex; gap: 0.5rem; padding: 1rem; justify-content: center; flex-wrap: wrap; }
.permissions-modal { position: fixed; inset: 0; z-index: 10001; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: auto; }
.permissions-modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.7); pointer-events: auto; cursor: default; }
.permissions-modal-box { position: relative; background: #fff; border-radius: 12px; max-width: 420px; width: 100%; padding: 1.5rem; box-shadow: 0 8px 32px rgba(0,0,0,0.3); pointer-events: auto; }
.item-card { position: relative; padding-top: 3rem; }
.item-card-actions { position: absolute; top: 0.5rem; right: 0.5rem; left: auto; display: flex; flex-direction: row; align-items: center; gap: 0.5rem; z-index: 2; flex-wrap: nowrap; }
.item-card-actions .edit-btn,
.item-card-actions .delete-btn { position: static !important; width: 34px; height: 34px; min-width: 34px; min-height: 34px; border: none; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; padding: 0; flex-shrink: 0; }
.item-card-actions .edit-btn { background: #2563eb; color: white; }
.item-card-actions .edit-btn:hover { opacity: 0.9; }
.item-card-actions .edit-btn svg { width: 16px; height: 16px; }
.item-card-actions .delete-btn { background: #f45c43; color: white; font-size: 1.25rem; line-height: 1; }
.item-card-actions .delete-btn:hover { opacity: 0.9; }
.edit-modal { position: fixed; inset: 0; z-index: 9998; display: flex; align-items: center; justify-content: center; padding: 1rem; }
.edit-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
.edit-modal-content { position: relative; background: #fff; border-radius: 12px; max-width: 480px; width: 100%; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
.edit-modal-header { flex-shrink: 0; display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px solid #eee; background: #fff; border-radius: 12px 12px 0 0; }
.edit-modal-header h3 { margin: 0; font-size: 1.15rem; }
.edit-modal-close { width: 36px; height: 36px; border: none; background: transparent; font-size: 1.5rem; color: #666; cursor: pointer; line-height: 1; padding: 0; }
.edit-modal-form { flex: 1; display: flex; flex-direction: column; min-height: 0; }
.edit-modal-body { flex: 1; overflow-y: auto; padding: 1.25rem; }
.edit-modal-footer { flex-shrink: 0; display: flex; align-items: center; gap: 0.5rem; justify-content: flex-end; padding: 1rem 1.25rem; border-top: 1px solid #eee; background: #fff; border-radius: 0 0 12px 12px; flex-wrap: wrap; }
.edit-autosave-status { font-size: 0.85rem; color: #155724; margin-right: auto; }
.edit-autosave-status.error { color: #721c24; }
.add-item-autosave-status { font-size: 0.875rem; color: #155724; }
.add-item-autosave-status.error { color: #721c24; }
/* Botões do modal: funções visuais distintas */
.btn-outline { background: #fff !important; color: #555 !important; border: 2px solid #cbd5e0 !important; }
.btn-outline:hover { background: #f1f5f9 !important; border-color: #94a3b8 !important; color: #334155 !important; }
.btn-cancel { background: #f1f5f9 !important; color: #475569 !important; border: 2px solid #cbd5e0 !important; }
.btn-cancel:hover { background: #e2e8f0 !important; border-color: #94a3b8 !important; color: #334155 !important; }

/* Carrossel da galeria do item */
.item-gallery-carousel { margin-top: 1rem; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; }
.carousel-viewport { position: relative; width: 100%; aspect-ratio: 4/3; max-height: 280px; background: #000; }
.carousel-viewport-clickable { cursor: pointer; }
.carousel-fullscreen-hint { position: absolute; top: 0.5rem; right: 0.5rem; z-index: 2; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; background: rgba(0,0,0,0.35); color: rgba(255,255,255,0.85); pointer-events: none; }
.carousel-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.3s; pointer-events: none; display: flex; align-items: center; justify-content: center; }
.carousel-slide.active { opacity: 1; pointer-events: none; z-index: 1; }
.carousel-viewport-clickable .carousel-slide.active { pointer-events: auto; }
.carousel-slide img { width: 100%; height: 100%; object-fit: contain; pointer-events: none; }
.carousel-controls { display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem; background: #fff; border-top: 1px solid #e2e8f0; flex-wrap: wrap; }
.carousel-btn { width: 36px; height: 36px; border: none; border-radius: 8px; background: #2563eb; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; }
.carousel-btn:hover { background: #5a67d8; }
.carousel-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.carousel-counter { font-size: 0.9rem; font-weight: 600; color: #475569; min-width: 3.5rem; text-align: center; }

/* Overlay tela cheia */
.carousel-fullscreen-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 10000; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; }
.carousel-fullscreen-overlay.show { display: flex; }
.carousel-fullscreen-content { position: relative; width: 100%; max-width: 100%; height: 100%; max-height: 100%; display: flex; align-items: center; justify-content: center; }
.carousel-fullscreen-content img { max-width: 100%; max-height: calc(100vh - 80px); object-fit: contain; }
.carousel-fullscreen-close { position: absolute; top: 0.5rem; right: 0.5rem; width: 44px; height: 44px; border: none; border-radius: 50%; background: rgba(255,255,255,0.2); color: white; cursor: pointer; font-size: 1.5rem; line-height: 1; z-index: 2; display: flex; align-items: center; justify-content: center; }
.carousel-fullscreen-close:hover { background: rgba(255,255,255,0.3); }
.carousel-fullscreen-controls { position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; background: rgba(0,0,0,0.6); border-radius: 12px; color: white; z-index: 2; }
.carousel-fullscreen-controls .carousel-btn { background: rgba(255,255,255,0.25); }
.carousel-fullscreen-controls .carousel-btn:hover { background: rgba(255,255,255,0.4); }
.carousel-fullscreen-controls .carousel-counter { color: white; }
</style>
@endpush

@push('scripts')
<script>
var tsConfig = { dropdownParent: 'body', maxOptions: null };
var tsConfigCreate = { dropdownParent: 'body', create: true, sortField: 'text', maxOptions: null };

var draftItemData = @json($draftItem ?? null);
var currentDraftItemId = draftItemData ? draftItemData.id : null;

var ambienteSelect = new TomSelect('#ambienteAtual', tsConfig);
ambienteSelect.on('change', function(v) { document.getElementById('localizacaoHidden').value = v || ''; scheduleDraftSave(); });
(function() {
    if (draftItemData) {
        ambienteSelect.setValue(draftItemData.localizacao || '');
        document.getElementById('localizacaoHidden').value = draftItemData.localizacao || '';
        if (document.getElementById('item')) document.getElementById('item').value = (draftItemData.item === '(em preenchimento)') ? '' : (draftItemData.item || '');
    } else {
        try {
            var saved = sessionStorage.getItem('vistoria_ambiente_{{ $inspection->id }}');
            if (saved) {
                ambienteSelect.setValue(saved);
                document.getElementById('localizacaoHidden').value = saved;
            }
        } catch (err) {}
    }
})();

var categoriaSelect = new TomSelect('#categoria', tsConfigCreate);
var marcaModeloSelect = new TomSelect('#marca_modelo', tsConfigCreate);
var estadoFisicoSelect = new TomSelect('#estado_fisico', tsConfig);
var funcionamentoSelect = new TomSelect('#funcionamento', tsConfig);
categoriaSelect.on('change', scheduleDraftSave);
marcaModeloSelect.on('change', scheduleDraftSave);
estadoFisicoSelect.on('change', scheduleDraftSave);
funcionamentoSelect.on('change', scheduleDraftSave);

if (draftItemData) {
    categoriaSelect.setValue(draftItemData.categoria || '');
    marcaModeloSelect.setValue(draftItemData.marca_modelo || '');
    estadoFisicoSelect.setValue(draftItemData.estado_fisico || '');
    funcionamentoSelect.setValue(draftItemData.funcionamento || '');
    var obsEl = document.querySelector('#itemForm textarea[name="observacoes"]');
    if (obsEl) obsEl.value = draftItemData.observacoes || '';
}

var editLocalizacaoSelect, editCategoriaSelect, editMarcaModeloSelect, editEstadoFisicoSelect, editFuncionamentoSelect;
var editSelectsInitialized = false;

function ensureEditSelects() {
    if (editSelectsInitialized) return;
    editSelectsInitialized = true;
    editLocalizacaoSelect = new TomSelect('#editLocalizacao', tsConfig);
    editCategoriaSelect = new TomSelect('#editCategoria', tsConfigCreate);
    editMarcaModeloSelect = new TomSelect('#editMarcaModelo', tsConfigCreate);
    editEstadoFisicoSelect = new TomSelect('#editEstadoFisico', tsConfig);
    editFuncionamentoSelect = new TomSelect('#editFuncionamento', tsConfig);
    editLocalizacaoSelect.on('change', scheduleEditAutosave);
    editCategoriaSelect.on('change', scheduleEditAutosave);
    editMarcaModeloSelect.on('change', scheduleEditAutosave);
    editEstadoFisicoSelect.on('change', scheduleEditAutosave);
    editFuncionamentoSelect.on('change', scheduleEditAutosave);
}

var selectedPhotos = [];

function addPhotoPreview(file, index) {
    var reader = new FileReader();
    reader.onload = function(ev) {
        var div = document.createElement('div');
        div.className = 'photo-preview-item';
        div.dataset.index = index;
        div.innerHTML = '<img src="' + ev.target.result + '" alt="Preview" class="photo-preview-thumb"><button type="button" class="photo-remove" data-index="' + index + '">×</button>';
        document.getElementById('photoPreviewList').appendChild(div);
        div.querySelector('.photo-remove').addEventListener('click', function() {
            selectedPhotos.splice(parseInt(this.dataset.index), 1);
            renderPreviews();
            scheduleDraftSave();
        });
    };
    reader.readAsDataURL(file);
}

function renderPreviews() {
    var list = document.getElementById('photoPreviewList');
    list.innerHTML = '';
    selectedPhotos.forEach(function(file, i) {
        addPhotoPreview(file, i);
    });
}

document.getElementById('btnGaleria').addEventListener('click', function() {
    document.getElementById('fotosInput').click();
});

document.getElementById('fotosInput').addEventListener('change', function(e) {
    var files = e.target.files;
    if (files && files.length) {
        for (var i = 0; i < files.length; i++) selectedPhotos.push(files[i]);
        renderPreviews();
        scheduleDraftSave();
    }
    this.value = '';
});

document.getElementById('cameraInput').addEventListener('change', function(e) {
    var files = e.target.files;
    if (files && files.length) {
        for (var i = 0; i < files.length; i++) selectedPhotos.push(files[i]);
        renderPreviews();
        scheduleDraftSave();
    }
    this.value = '';
});

var draftSaveTimeout = null;
var DRAFT_SAVE_DELAY = 1000;

function getGeolocation() {
    return new Promise(function(resolve) {
        if (!navigator.geolocation) { resolve(null); return; }
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                resolve({
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    accuracy: pos.coords.accuracy != null ? pos.coords.accuracy : null
                });
            },
            function() { resolve(null); },
            { timeout: 8000, maximumAge: 60000, enableHighAccuracy: true }
        );
    });
}

async function appendGeolocationToFormData(formData) {
    var coords = await getGeolocation();
    if (coords) {
        formData.append('latitude', coords.latitude);
        formData.append('longitude', coords.longitude);
        if (coords.accuracy != null) formData.append('geolocation_accuracy', coords.accuracy);
    }
}

(function checkPermissionsAndShowModalIfNeeded() {
    var modal = document.getElementById('permissionsModal');
    var statusEl = document.getElementById('permissionsModalStatus');
    if (!modal) return;
    function showModal() {
        modal.style.display = 'flex';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function hideModal() {
        modal.style.display = 'none';
    }
    function setStatus(text) {
        if (statusEl) statusEl.textContent = text;
    }
    function permissionStateNotGranted(state) {
        return state && state !== 'granted';
    }
    function recheckAndHideIfGranted() {
        var q = navigator.permissions && navigator.permissions.query;
        if (!q) { hideModal(); return; }
        Promise.all([
            navigator.permissions.query({ name: 'geolocation' }).then(function(p) { return p.state; }).catch(function() { return 'granted'; }),
            navigator.permissions.query({ name: 'camera' }).then(function(p) { return p.state; }).catch(function() { return 'granted'; })
        ]).then(function(results) {
            if (!permissionStateNotGranted(results[0]) && !permissionStateNotGranted(results[1])) {
                setStatus('');
                hideModal();
            }
        }).catch(function() {});
    }
    function requestPermissions() {
        var btn = document.getElementById('btnGrantPermission');
        if (btn) { btn.disabled = true; }
        setStatus('Solicitando... O navegador pode pedir localização e câmera.');
        if (typeof lucide !== 'undefined') lucide.createIcons();
        function doCamera() {
            if (!navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
                setStatus('Câmera não disponível neste navegador.');
                if (btn) btn.disabled = false;
                recheckAndHideIfGranted();
                return;
            }
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(function(stream) {
                    stream.getTracks().forEach(function(t) { t.stop(); });
                    setStatus('Permissões solicitadas. Verificando...');
                    if (btn) btn.disabled = false;
                    recheckAndHideIfGranted();
                })
                .catch(function() {
                    setStatus('Câmera negada ou indisponível. Ative nas configurações se quiser usar.');
                    if (btn) btn.disabled = false;
                    recheckAndHideIfGranted();
                });
        }
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function() { doCamera(); },
                function() {
                    setStatus('Localização negada. Solicitando câmera...');
                    doCamera();
                },
                { timeout: 15000, maximumAge: 0 }
            );
        } else {
            doCamera();
        }
    }
    document.getElementById('btnGrantPermission') && document.getElementById('btnGrantPermission').addEventListener('click', requestPermissions);
    Promise.all([
        typeof navigator.permissions !== 'undefined' && navigator.permissions.query
            ? navigator.permissions.query({ name: 'geolocation' }).then(function(p) { return p.state; }).catch(function() { return 'granted'; })
            : Promise.resolve('granted'),
        typeof navigator.permissions !== 'undefined' && navigator.permissions.query
            ? navigator.permissions.query({ name: 'camera' }).then(function(p) { return p.state; }).catch(function() { return 'granted'; })
            : Promise.resolve('granted')
    ]).then(function(results) {
        var geo = results[0];
        var cam = results[1];
        if (permissionStateNotGranted(geo) || permissionStateNotGranted(cam)) {
            showModal();
        }
    }).catch(function() {});
})();

function showAddItemAutosaveStatus(msg, isError) {
    var el = document.getElementById('addItemAutosaveStatus');
    if (!el) return;
    el.textContent = msg;
    el.className = 'add-item-autosave-status' + (isError ? ' error' : '');
    if (msg) setTimeout(function() { el.textContent = ''; el.className = 'add-item-autosave-status'; }, 2500);
}

function hasAmbienteForDraft() {
    document.getElementById('localizacaoHidden').value = ambienteSelect.getValue() || '';
    var form = document.getElementById('itemForm');
    var loc = (form.elements['localizacao'] && form.elements['localizacao'].value) || '';
    return loc.trim() !== '';
}

function scheduleDraftSave() {
    if (draftSaveTimeout) clearTimeout(draftSaveTimeout);
    draftSaveTimeout = setTimeout(saveDraft, DRAFT_SAVE_DELAY);
}

async function saveDraft() {
    document.getElementById('localizacaoHidden').value = ambienteSelect.getValue() || '';
    var form = document.getElementById('itemForm');
    if (currentDraftItemId) {
        var formData = new FormData(form);
        formData.set('_method', 'PUT');
        var itemVal = (form.elements['item'] && form.elements['item'].value) ? form.elements['item'].value.trim() : '';
        var estadoVal = (form.elements['estado_fisico'] && form.elements['estado_fisico'].value) || '';
        var funcVal = (form.elements['funcionamento'] && form.elements['funcionamento'].value) || '';
        formData.set('item', itemVal || '(em preenchimento)');
        formData.set('estado_fisico', estadoVal || 'Não se aplica');
        formData.set('funcionamento', funcVal || 'Não se aplica');
        selectedPhotos.forEach(function(f) { formData.append('fotos[]', f); });
        try {
            var r = await fetch('{{ url("/inspections/items") }}/' + currentDraftItemId, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Autosave': '1' }
            });
            var data = await r.json().catch(function() { return {}; });
            if (r.ok && data.success) showAddItemAutosaveStatus('Salvo');
            else showAddItemAutosaveStatus('Erro ao salvar', true);
        } catch (e) { showAddItemAutosaveStatus('Erro ao salvar', true); }
        return;
    }
    if (!hasAmbienteForDraft()) return;
    var formData = new FormData(form);
    var itemVal = (form.elements['item'] && form.elements['item'].value) ? form.elements['item'].value.trim() : '';
    var estadoVal = (form.elements['estado_fisico'] && form.elements['estado_fisico'].value) || '';
    var funcVal = (form.elements['funcionamento'] && form.elements['funcionamento'].value) || '';
    formData.set('item', itemVal || '(em preenchimento)');
    formData.set('estado_fisico', estadoVal || 'Não se aplica');
    formData.set('funcionamento', funcVal || 'Não se aplica');
    selectedPhotos.forEach(function(f) { formData.append('fotos[]', f); });
    try {
        var r = await fetch('{{ route("inspections.items.store", $inspection) }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        });
        var data = await r.json().catch(function() { return {}; });
        if (r.ok && data.success && data.id) {
            currentDraftItemId = data.id;
            showAddItemAutosaveStatus('Rascunho criado. Alterações salvas automaticamente.');
        } else if (!r.ok) showAddItemAutosaveStatus('Erro ao salvar', true);
    } catch (e) { showAddItemAutosaveStatus('Erro ao salvar', true); }
}

var itemFormEl = document.getElementById('itemForm');
itemFormEl.addEventListener('input', scheduleDraftSave);
itemFormEl.addEventListener('change', scheduleDraftSave);

var cameraStream = null;
var cameraVideo = document.getElementById('cameraVideo');
var cameraModal = document.getElementById('cameraModal');
var cameraLoading = document.getElementById('cameraLoading');
var cameraLiveWrap = document.getElementById('cameraLiveWrap');
var cameraPreviewWrap = document.getElementById('cameraPreviewWrap');
var cameraPreviewImg = document.getElementById('cameraPreviewImg');
var btnCapture = document.getElementById('btnCapture');
var pendingCaptureFile = null;
var cameraTarget = 'add';

function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (navigator.maxTouchPoints && navigator.maxTouchPoints > 2);
}

var cameraActionsLive = document.getElementById('cameraActionsLive');
var cameraActionsPreview = document.getElementById('cameraActionsPreview');

function showCameraPreview(file) {
    pendingCaptureFile = file;
    var url = URL.createObjectURL(file);
    cameraPreviewImg.src = url;
    cameraLiveWrap.classList.remove('visible');
    cameraPreviewWrap.classList.add('visible');
    if (cameraActionsLive) cameraActionsLive.style.display = 'none';
    if (cameraActionsPreview) cameraActionsPreview.style.display = 'flex';
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function backToCameraLive() {
    if (cameraPreviewImg.src) URL.revokeObjectURL(cameraPreviewImg.src);
    cameraPreviewImg.removeAttribute('src');
    cameraPreviewWrap.classList.remove('visible');
    cameraLiveWrap.classList.add('visible');
    if (cameraActionsPreview) cameraActionsPreview.style.display = 'none';
    if (cameraActionsLive) cameraActionsLive.style.display = 'flex';
    pendingCaptureFile = null;
}

function closeCameraModal() {
    if (pendingCaptureFile) {
        if (cameraTarget === 'edit') {
            editSelectedPhotos.push(pendingCaptureFile);
            renderEditPreviews();
        } else {
            selectedPhotos.push(pendingCaptureFile);
            renderPreviews();
            scheduleDraftSave();
        }
        pendingCaptureFile = null;
    }
    if (cameraPreviewImg.src) URL.revokeObjectURL(cameraPreviewImg.src);
    cameraPreviewImg.removeAttribute('src');
    cameraPreviewWrap.classList.remove('visible');
    cameraLiveWrap.classList.add('visible');
    if (cameraActionsPreview) cameraActionsPreview.style.display = 'none';
    if (cameraActionsLive) cameraActionsLive.style.display = 'flex';
    if (cameraStream) {
        cameraStream.getTracks().forEach(function(t) { t.stop(); });
    }
    cameraStream = null;
    cameraVideo.srcObject = null;
    cameraVideo.style.display = 'none';
    cameraVideo.classList.remove('ready');
    cameraModal.style.display = 'none';
}

function startCameraModal() {
    cameraModal.style.display = 'flex';
    cameraLoading.textContent = 'Abrindo câmera...';
    cameraLoading.style.display = 'flex';
    btnCapture.disabled = true;
    cameraVideo.classList.remove('ready');
    cameraVideo.style.display = 'none';
    cameraLiveWrap.classList.remove('visible');
    cameraPreviewWrap.classList.remove('visible');
    if (cameraActionsPreview) cameraActionsPreview.style.display = 'none';
    if (cameraActionsLive) cameraActionsLive.style.display = 'flex';
    pendingCaptureFile = null;
    if (cameraPreviewImg.src) URL.revokeObjectURL(cameraPreviewImg.src);
    cameraPreviewImg.removeAttribute('src');

    var constraintsList = [
        { video: { facingMode: 'environment', width: { max: 1280 }, height: { max: 720 } }, audio: false },
        { video: { facingMode: 'user', width: { max: 1280 }, height: { max: 720 } }, audio: false },
        { video: { facingMode: 'environment', width: { max: 1024 }, height: { max: 768 } }, audio: false },
        { video: { facingMode: 'user', width: { max: 1024 }, height: { max: 768 } }, audio: false },
        { video: true }
    ];

    function tryNext(index) {
        if (index >= constraintsList.length) {
            cameraLoading.textContent = '';
            cameraModal.style.display = 'none';
            if (isMobileDevice()) {
                Swal.fire({
                    icon: 'info',
                    title: 'Câmera',
                    text: 'Não foi possível abrir a câmera no navegador. Use a câmera do sistema ou a galeria.',
                    showCancelButton: true,
                    confirmButtonText: 'Câmera do sistema',
                    cancelButtonText: 'Galeria',
                    confirmButtonColor: '#2563eb'
                }).then(function(r) {
                    if (cameraTarget === 'edit') {
                        if (r.isConfirmed) document.getElementById('editCameraInput').click();
                        else document.getElementById('editFotosInput').click();
                    } else {
                        if (r.isConfirmed) document.getElementById('cameraInput').click();
                        else document.getElementById('fotosInput').click();
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível acessar a câmera. Verifique as permissões ou use o botão Galeria.' });
            }
            return;
        }
        navigator.mediaDevices.getUserMedia(constraintsList[index])
            .then(function(stream) {
                cameraStream = stream;
                cameraVideo.setAttribute('playsinline', '');
                cameraVideo.setAttribute('webkit-playsinline', '');
                cameraVideo.srcObject = stream;
                cameraVideo.style.display = 'block';
                cameraVideo.onloadeddata = function() {
                    var p = cameraVideo.play();
                    if (p && typeof p.then === 'function') {
                        p.then(function() {
                            cameraVideo.classList.add('ready');
                            cameraLoading.style.display = 'none';
                            cameraLiveWrap.classList.add('visible');
                            btnCapture.disabled = false;
                        }).catch(function() {
                            cameraVideo.classList.add('ready');
                            cameraLoading.style.display = 'none';
                            cameraLiveWrap.classList.add('visible');
                            btnCapture.disabled = false;
                        });
                    } else {
                        cameraVideo.classList.add('ready');
                        cameraLoading.style.display = 'none';
                        cameraLiveWrap.classList.add('visible');
                        btnCapture.disabled = false;
                    }
                };
                cameraVideo.onerror = function() { tryNext(index + 1); };
            })
            .catch(function() { tryNext(index + 1); });
    }
    tryNext(0);
}

function openCameraForAdd() {
    cameraTarget = 'add';
    if (typeof navigator.mediaDevices !== 'undefined' && typeof navigator.mediaDevices.getUserMedia === 'function') {
        startCameraModal();
    } else if (isMobileDevice()) {
        document.getElementById('cameraInput').click();
    } else {
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Câmera não disponível. Use o botão Galeria.' });
    }
}

document.getElementById('btnCamera').addEventListener('click', openCameraForAdd);

document.getElementById('btnCloseCamera').addEventListener('click', function() {
    closeCameraModal();
});

var CAPTURE_MAX_SIZE = 1280;
var CAPTURE_JPEG_QUALITY = 0.82;

document.getElementById('btnCapture').addEventListener('click', function() {
    if (!cameraVideo.videoWidth || !cameraVideo.videoHeight) return;
    var video = cameraVideo;
    function doCapture() {
        var w = video.videoWidth;
        var h = video.videoHeight;
        var scale = 1;
        if (w > CAPTURE_MAX_SIZE || h > CAPTURE_MAX_SIZE) {
            scale = w > h ? CAPTURE_MAX_SIZE / w : CAPTURE_MAX_SIZE / h;
        }
        var cw = Math.round(w * scale);
        var ch = Math.round(h * scale);
        var canvas = document.createElement('canvas');
        canvas.width = cw;
        canvas.height = ch;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, w, h, 0, 0, cw, ch);
        canvas.toBlob(function(blob) {
            if (!blob) return;
            var file = new File([blob], 'captura-' + Date.now() + '.jpg', { type: 'image/jpeg' });
            showCameraPreview(file);
        }, 'image/jpeg', CAPTURE_JPEG_QUALITY);
    }
    function scheduleCapture() {
        requestAnimationFrame(function() {
            requestAnimationFrame(doCapture);
        });
    }
    if (video.readyState >= 2) {
        scheduleCapture();
    } else {
        video.addEventListener('canplay', function onCanPlay() {
            video.removeEventListener('canplay', onCanPlay);
            setTimeout(scheduleCapture, 200);
        }, { once: true });
    }
});

document.getElementById('btnAddMorePhoto').addEventListener('click', function() {
    if (pendingCaptureFile) {
        if (cameraTarget === 'edit') {
            editSelectedPhotos.push(pendingCaptureFile);
            renderEditPreviews();
        } else {
            selectedPhotos.push(pendingCaptureFile);
            renderPreviews();
            scheduleDraftSave();
        }
    }
    backToCameraLive();
    if (typeof lucide !== 'undefined') lucide.createIcons();
});

document.getElementById('btnUsePhotos').addEventListener('click', function() {
    closeCameraModal();
    if (typeof lucide !== 'undefined') lucide.createIcons();
});

document.getElementById('itemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (document.activeElement && document.activeElement.blur) document.activeElement.blur();
    var itemInput = document.getElementById('item');
    var itemName = (itemInput && itemInput.value) ? String(itemInput.value).trim() : '';
    if (!itemName && this.elements['item'] && this.elements['item'].value) itemName = String(this.elements['item'].value).trim();
    
    var ambiente = ambienteSelect.getValue();
    if (!ambiente) {
        Swal.fire({
            icon: 'warning',
            title: 'Ambiente obrigatório',
            text: 'Selecione o ambiente antes de adicionar o item.'
        });
        return;
    }
    if (!itemName || itemName === '(em preenchimento)') {
        Swal.fire({
            icon: 'warning',
            title: 'Nome do item obrigatório',
            text: 'Informe o nome do item no campo "Item" (ex: Geladeira, Sofá).'
        });
        if (itemInput) itemInput.focus();
        return;
    }
    document.getElementById('localizacaoHidden').value = ambiente;
    
    var submitBtn = document.getElementById('btnAddItem');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Salvando...';
    
    try {
        if (currentDraftItemId) {
            var formData = new FormData(this);
            formData.set('_method', 'PUT');
            formData.set('estado_fisico', (this.elements['estado_fisico'] && this.elements['estado_fisico'].value) || 'Não se aplica');
            formData.set('funcionamento', (this.elements['funcionamento'] && this.elements['funcionamento'].value) || 'Não se aplica');
            selectedPhotos.forEach(function(file) { formData.append('fotos[]', file); });
            await appendGeolocationToFormData(formData);
            var response = await fetch('{{ url("/inspections/items") }}/' + currentDraftItemId, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            });
            var data = await response.json().catch(function() { return {}; });
            if (response.ok && data.success) {
                try { sessionStorage.setItem('vistoria_ambiente_{{ $inspection->id }}', ambiente || ''); } catch (err) {}
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: 'Item salvo.', confirmButtonColor: '#2563eb' }).then(function() { window.location.reload(); });
            } else throw new Error(data.message || 'Erro ao salvar');
        } else {
            var formData = new FormData(this);
            selectedPhotos.forEach(function(file) { formData.append('fotos[]', file); });
            await appendGeolocationToFormData(formData);
            var response = await fetch('{{ route("inspections.items.store", $inspection) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            });
            var data = await response.json().catch(function() { return {}; });
            if (response.ok && data.success) {
                try { sessionStorage.setItem('vistoria_ambiente_{{ $inspection->id }}', ambiente || ''); } catch (err) {}
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message, confirmButtonColor: '#2563eb' }).then(function() { window.location.reload(); });
            } else {
                var msg = (data.errors ? Object.values(data.errors).flat().join('\n') : null) || data.message || 'Erro ao adicionar item';
                throw new Error(msg);
            }
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Erro', text: error.message || 'Erro ao salvar' });
    }
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
});

var editingItemId = null;
var editSelectedPhotos = [];
var editExistingPhotos = [];
var editLegacyRemoved = false;
var storageBaseUrl = "{{ asset('storage') }}";

function openEditItem(btn) {
    var card = btn.closest('.item-card');
    if (!card) return;
    editingItemId = card.dataset.itemId;
    document.getElementById('editItemModal').style.display = 'flex';
    ensureEditSelects();
    editLocalizacaoSelect.setValue(card.dataset.localizacao || '');
    editCategoriaSelect.setValue(card.dataset.categoria || '');
    document.getElementById('editItem').value = card.dataset.item || '';
    editMarcaModeloSelect.setValue(card.dataset.marcaModelo || '');
    editEstadoFisicoSelect.setValue(card.dataset.estadoFisico || '');
    editFuncionamentoSelect.setValue(card.dataset.funcionamento || '');
    document.getElementById('editObservacoes').value = card.dataset.observacoes || '';
    editSelectedPhotos = [];
    editExistingPhotos = [];
    editLegacyRemoved = false;
    try {
        editExistingPhotos = JSON.parse(card.dataset.photos || '[]') || [];
    } catch (e) { editExistingPhotos = []; }
    if ((!editExistingPhotos || editExistingPhotos.length === 0) && card.dataset.legacyFoto) {
        editExistingPhotos = [{ id: null, path: card.dataset.legacyFoto, url: storageBaseUrl + '/' + card.dataset.legacyFoto, legacy: true }];
    }
    renderEditPreviews();
    var statusEl = document.getElementById('editAutosaveStatus');
    if (statusEl) { statusEl.textContent = ''; statusEl.className = 'edit-autosave-status'; }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeEditItem() {
    document.getElementById('editItemModal').style.display = 'none';
    editingItemId = null;
    editSelectedPhotos = [];
    editExistingPhotos = [];
    editLegacyRemoved = false;
}

function addEditPhotoPreview(file, index) {
    var reader = new FileReader();
    reader.onload = function(ev) {
        var div = document.createElement('div');
        div.className = 'photo-preview-item';
        div.dataset.index = index;
        div.dataset.type = 'new';
        div.innerHTML = '<img src="' + ev.target.result + '" alt="Preview" class="photo-preview-thumb"><button type="button" class="photo-remove" data-index="' + index + '">×</button>';
        document.getElementById('editPhotoPreviewList').appendChild(div);
        div.querySelector('.photo-remove').addEventListener('click', function() {
            editSelectedPhotos.splice(parseInt(this.dataset.index), 1);
            renderEditPreviews();
        });
    };
    reader.readAsDataURL(file);
}

function renderEditPreviews() {
    var list = document.getElementById('editPhotoPreviewList');
    list.innerHTML = '';
    if (editExistingPhotos && editExistingPhotos.length) {
        editExistingPhotos.forEach(function(photo, i) {
            var div = document.createElement('div');
            div.className = 'photo-preview-item';
            div.dataset.index = i;
            div.dataset.type = 'existing';
            div.innerHTML = '<img src="' + photo.url + '" alt="Foto" class="photo-preview-thumb"><button type="button" class="photo-remove" data-index="' + i + '" data-type="existing">×</button>';
            list.appendChild(div);
            div.querySelector('.photo-remove').addEventListener('click', function() {
                var idx = parseInt(this.dataset.index, 10);
                var removed = editExistingPhotos.splice(idx, 1)[0];
                if (removed && (removed.id === null || removed.legacy)) {
                    editLegacyRemoved = true;
                }
                renderEditPreviews();
            });
        });
    }
    editSelectedPhotos.forEach(function(file, i) { addEditPhotoPreview(file, i); });
}

document.getElementById('editBtnGaleria').addEventListener('click', function() {
    document.getElementById('editFotosInput').click();
});

document.getElementById('editBtnCamera').addEventListener('click', function() {
    cameraTarget = 'edit';
    if (typeof navigator.mediaDevices !== 'undefined' && typeof navigator.mediaDevices.getUserMedia === 'function') {
        startCameraModal();
    } else if (isMobileDevice()) {
        document.getElementById('editCameraInput').click();
    } else {
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Câmera não disponível. Use o botão Galeria.' });
    }
});

document.getElementById('editCameraInput').addEventListener('change', function(e) {
    var files = e.target.files;
    if (files && files.length) {
        for (var i = 0; i < files.length; i++) editSelectedPhotos.push(files[i]);
        renderEditPreviews();
    }
    this.value = '';
});

document.getElementById('editFotosInput').addEventListener('change', function(e) {
    var files = e.target.files;
    if (files && files.length) {
        for (var i = 0; i < files.length; i++) editSelectedPhotos.push(files[i]);
        renderEditPreviews();
    }
    this.value = '';
});

document.getElementById('editItemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!editingItemId) return;
    var formData = new FormData(this);
    formData.delete('keep_photo_ids[]');
    formData.delete('keep_photo_ids');
    formData.delete('remove_legacy_foto');
    var keptIds = [];
    if (editExistingPhotos && editExistingPhotos.length) {
        editExistingPhotos.forEach(function(p) {
            if (p && p.id !== null && typeof p.id !== 'undefined') {
                keptIds.push(Number(p.id));
            }
        });
    }
    formData.append('keep_photo_ids', JSON.stringify(keptIds));
    if (editLegacyRemoved) {
        formData.append('remove_legacy_foto', '1');
    }
    editSelectedPhotos.forEach(function(file) { formData.append('fotos[]', file); });
    await appendGeolocationToFormData(formData);
    var saveBtn = document.getElementById('btnSaveEditItem');
    var originalHtml = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = 'Salvando...';
    var updateUrl = '{{ url("/inspections/items") }}/' + editingItemId;
    try {
        var response = await fetch(updateUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        });
        var data = await response.json().catch(function() { return {}; });
        if (response.ok && data.success) {
            closeEditItem();
            Swal.fire({ icon: 'success', title: 'Salvo!', text: data.message, confirmButtonColor: '#2563eb' }).then(function() {
                window.location.reload();
            });
        } else {
            var msg = (data.errors ? Object.values(data.errors).flat().join('\n') : null) || data.message || 'Erro ao salvar';
            throw new Error(msg);
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Erro', text: err.message || 'Erro ao salvar' });
    }
    saveBtn.disabled = false;
    saveBtn.innerHTML = originalHtml;
});

var editAutosaveTimeout = null;
var editAutosaveInProgress = false;
var EDIT_AUTOSAVE_DELAY = 1000;

function showEditAutosaveStatus(msg, isError) {
    var el = document.getElementById('editAutosaveStatus');
    if (!el) return;
    el.textContent = msg;
    el.className = 'edit-autosave-status' + (isError ? ' error' : '');
    if (msg) setTimeout(function() { el.textContent = ''; el.className = 'edit-autosave-status'; }, 2500);
}

async function saveEditItemAutosave() {
    if (!editingItemId || editAutosaveInProgress) return;
    editAutosaveInProgress = true;
    var form = document.getElementById('editItemForm');
    var formData = new FormData(form);
    var updateUrl = '{{ url("/inspections/items") }}/' + editingItemId;
    try {
        var response = await fetch(updateUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Autosave': '1' }
        });
        var data = await response.json().catch(function() { return {}; });
        if (response.ok && data.success) showEditAutosaveStatus('Salvo');
        else showEditAutosaveStatus('Erro ao salvar', true);
    } catch (err) {
        showEditAutosaveStatus('Erro ao salvar', true);
    }
    editAutosaveInProgress = false;
}

function scheduleEditAutosave() {
    if (!editingItemId) return;
    if (editAutosaveTimeout) clearTimeout(editAutosaveTimeout);
    editAutosaveTimeout = setTimeout(saveEditItemAutosave, EDIT_AUTOSAVE_DELAY);
}

document.getElementById('editItem').addEventListener('input', scheduleEditAutosave);
document.getElementById('editItem').addEventListener('change', scheduleEditAutosave);
document.getElementById('editObservacoes').addEventListener('input', scheduleEditAutosave);
document.getElementById('editObservacoes').addEventListener('change', scheduleEditAutosave);

async function deleteItem(id) {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover este item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f45c43',
        cancelButtonColor: '#2563eb',
        confirmButtonText: 'Sim, remover!',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/inspections/items/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Removido!',
                    text: data.message,
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#2563eb'
                }).then(function() {
                    window.location.reload();
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao remover item'
            });
        }
    }
}

(function() {
    function getCarouselIndex(carousel) {
        var active = carousel.querySelector('.carousel-slide.active');
        return active ? parseInt(active.dataset.index, 10) : 0;
    }
    function setCarouselIndex(carousel, index) {
        var total = parseInt(carousel.dataset.total, 10);
        if (index < 0) index = 0;
        if (index >= total) index = total - 1;
        var slides = carousel.querySelectorAll('.carousel-slide');
        slides.forEach(function(s) { s.classList.remove('active'); });
        var slide = carousel.querySelector('.carousel-slide[data-index="' + index + '"]');
        if (slide) slide.classList.add('active');
        var counterEl = carousel.querySelector('.carousel-current');
        if (counterEl) counterEl.textContent = index + 1;
        var prevBtn = carousel.querySelector('.carousel-prev');
        var nextBtn = carousel.querySelector('.carousel-next');
        if (prevBtn) prevBtn.disabled = index <= 0;
        if (nextBtn) nextBtn.disabled = index >= total - 1;
        return index;
    }
    document.querySelectorAll('.item-gallery-carousel').forEach(function(c) { setCarouselIndex(c, getCarouselIndex(c)); });
    document.addEventListener('click', function(e) {
        var viewport = e.target.closest('.carousel-viewport-clickable');
        if (viewport) {
            var carousel = viewport.closest('.item-gallery-carousel');
            if (carousel) {
                var idx = getCarouselIndex(carousel);
                window._fullscreenCarousel = carousel;
                window._fullscreenIndex = idx;
                var slide = carousel.querySelector('.carousel-slide.active img');
                if (slide) {
                    document.getElementById('carouselFullscreenImg').src = slide.src;
                    document.getElementById('carouselFullscreenCounter').textContent = idx + 1;
                    document.getElementById('carouselFullscreenTotal').textContent = carousel.dataset.total;
                    document.getElementById('carouselFullscreenOverlay').classList.add('show');
                    document.body.style.overflow = 'hidden';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }
            return;
        }
        var btn = e.target.closest('.carousel-prev, .carousel-next');
        if (!btn) return;
        var carousel = btn.closest('.item-gallery-carousel');
        if (!carousel) return;
        var idx = getCarouselIndex(carousel);
        if (btn.classList.contains('carousel-prev')) idx = setCarouselIndex(carousel, idx - 1);
        if (btn.classList.contains('carousel-next')) idx = setCarouselIndex(carousel, idx + 1);
    });
})();

function closeCarouselFullscreen() {
    document.getElementById('carouselFullscreenOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('carouselFullscreenOverlay').classList.contains('show')) {
        closeCarouselFullscreen();
    }
});
function fullscreenPrev() {
    var carousel = window._fullscreenCarousel;
    if (!carousel) return;
    var total = parseInt(carousel.dataset.total, 10);
    var idx = (window._fullscreenIndex || 0) - 1;
    if (idx < 0) idx = total - 1;
    window._fullscreenIndex = idx;
    var slide = carousel.querySelector('.carousel-slide[data-index="' + idx + '"] img');
    if (slide) {
        document.getElementById('carouselFullscreenImg').src = slide.src;
        document.getElementById('carouselFullscreenCounter').textContent = idx + 1;
    }
}
function fullscreenNext() {
    var carousel = window._fullscreenCarousel;
    if (!carousel) return;
    var total = parseInt(carousel.dataset.total, 10);
    var idx = (window._fullscreenIndex || 0) + 1;
    if (idx >= total) idx = 0;
    window._fullscreenIndex = idx;
    var slide = carousel.querySelector('.carousel-slide[data-index="' + idx + '"] img');
    if (slide) {
        document.getElementById('carouselFullscreenImg').src = slide.src;
        document.getElementById('carouselFullscreenCounter').textContent = idx + 1;
    }
}
</script>
@endpush
@endsection
