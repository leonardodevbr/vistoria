@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-primary btn-sm btn-icon"><i data-lucide="pencil" width="16" height="16"></i> Editar dados da vistoria</a>
        <a href="{{ route('inspections.index') }}" class="btn btn-primary btn-sm btn-icon"><i data-lucide="arrow-left" width="16" height="16"></i> Voltar à lista</a>
    </div>
    
    <h2 class="icon-wrap" style="margin-bottom: 0.5rem;"><i data-lucide="clipboard-list" width="22" height="22"></i> Vistoria #{{ $inspection->id }}</h2>
    @if($inspection->endereco)
        <p style="color: #666; margin-bottom: 1rem;">{{ $inspection->endereco }}</p>
    @endif
</div>

<div class="card">
    <h3 class="icon-wrap" style="margin-bottom: 1rem;"><i data-lucide="layout-grid" width="20" height="20"></i> Adicionar itens por ambiente</h3>
    <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Selecione o ambiente e adicione todos os itens desse ambiente. Depois altere o ambiente para cadastrar itens de outro cômodo.</p>
    
    <div class="form-group">
        <label class="form-label icon-wrap"><i data-lucide="door-open" width="14" height="14"></i> Ambiente atual *</label>
        <select id="ambienteAtual" class="form-control" required>
            <option value="">Selecione o ambiente...</option>
            <option value="Cozinha">Cozinha</option>
            <option value="Sala">Sala</option>
            <option value="Quarto">Quarto</option>
            <option value="Quarto casal">Quarto casal</option>
            <option value="Quarto 2">Quarto 2</option>
            <option value="Banheiro">Banheiro</option>
            <option value="Banheiro social">Banheiro social</option>
            <option value="Banheiro suíte">Banheiro suíte</option>
            <option value="Área de serviço">Área de serviço</option>
            <option value="Varanda">Varanda</option>
            <option value="Garagem">Garagem</option>
        </select>
    </div>
    
    <form id="itemForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="localizacao" id="localizacaoHidden" value="">
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="folder" width="14" height="14"></i> Categoria</label>
            <select id="categoria" name="categoria" class="form-control">
                <option value="">Selecione ou digite...</option>
                <option value="Eletrodomésticos">Eletrodomésticos</option>
                <option value="Móveis">Móveis</option>
                <option value="Planejados">Planejados</option>
                <option value="Piso">Piso</option>
                <option value="Banheiro">Banheiro</option>
                <option value="Cozinha">Cozinha</option>
                <option value="Quartos">Quartos</option>
                <option value="Sala">Sala</option>
                <option value="Área de Serviço">Área de Serviço</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="tag" width="14" height="14"></i> Item *</label>
            <input type="text" id="item" name="item" class="form-control" required
                   placeholder="Ex: Geladeira, Sofá, Armário...">
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="package" width="14" height="14"></i> Marca/Modelo</label>
            <select id="marca_modelo" name="marca_modelo" class="form-control">
                <option value="">Selecione ou digite...</option>
                <option value="AOC">AOC</option>
                <option value="Arno">Arno</option>
                <option value="Atlas">Atlas</option>
                <option value="Black+Decker">Black+Decker</option>
                <option value="Bosch">Bosch</option>
                <option value="Brastemp">Brastemp</option>
                <option value="Britânia">Britânia</option>
                <option value="Consul">Consul</option>
                <option value="Dako">Dako</option>
                <option value="Daikin">Daikin</option>
                <option value="Dolce Gusto">Dolce Gusto</option>
                <option value="Dyson">Dyson</option>
                <option value="Electrolux">Electrolux</option>
                <option value="Fischer">Fischer</option>
                <option value="Gree">Gree</option>
                <option value="Hisense">Hisense</option>
                <option value="Karcher">Karcher</option>
                <option value="LG">LG</option>
                <option value="Midea">Midea</option>
                <option value="Mondial">Mondial</option>
                <option value="Mueller">Mueller</option>
                <option value="Nespresso">Nespresso</option>
                <option value="Oster">Oster</option>
                <option value="Panasonic">Panasonic</option>
                <option value="Philco">Philco</option>
                <option value="Philips Walita">Philips Walita</option>
                <option value="Samsung">Samsung</option>
                <option value="Sony">Sony</option>
                <option value="Springer Carrier">Springer Carrier</option>
                <option value="Suggar">Suggar</option>
                <option value="TCL">TCL</option>
                <option value="Tramontina">Tramontina</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="star" width="14" height="14"></i> Estado físico *</label>
            <select name="estado_fisico" class="form-control" required>
                <option value="">Selecione...</option>
                <option value="Novo">Novo</option>
                <option value="Seminovo">Seminovo</option>
                <option value="Ótimo">Ótimo</option>
                <option value="Bom">Bom</option>
                <option value="Regular">Regular</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="settings" width="14" height="14"></i> Funcionamento *</label>
            <select name="funcionamento" class="form-control" required>
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
            <textarea name="observacoes" class="form-control" 
                      placeholder="Detalhe riscos, manchas, folgas, etc."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="camera" width="14" height="14"></i> Fotos</label>
            <input type="file" id="fotosInput" accept="image/*" multiple style="display: none;">
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
                <video id="cameraVideo" autoplay playsinline></video>
                <div class="camera-modal-actions">
                    <button type="button" id="btnCapture" class="btn btn-success">Capturar</button>
                    <button type="button" id="btnCloseCamera" class="btn btn-primary">Fechar</button>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success btn-icon" id="btnAddItem">
            <i data-lucide="plus-circle" width="18" height="18"></i> Adicionar item
        </button>
    </form>
</div>

<div id="itemsList">
    @if($inspection->items->count() > 0)
        <div class="card">
            <h3 class="icon-wrap" style="margin-bottom: 1rem;"><i data-lucide="list-checks" width="20" height="20"></i> Itens cadastrados ({{ $inspection->items->count() }})</h3>
            
            @foreach($inspection->items->groupBy('localizacao') as $localizacao => $items)
                <h4 style="color: #667eea; margin-top: 1.5rem; margin-bottom: 1rem;">
                    {{ $localizacao ?: 'Sem localização' }}
                </h4>
                
                @foreach($items as $item)
                    <div class="item-card">
                        <button type="button" class="delete-btn" onclick="deleteItem({{ $item->id }})">
                            ×
                        </button>
                        
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
                        
                                        @foreach($item->allPhotos() as $photoPath)
                            <img src="{{ asset('storage/' . $photoPath) }}" 
                                 class="photo-preview" 
                                 alt="Foto do item">
                        @endforeach
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

@push('styles')
<style>
.foto-actions { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; }
.photo-preview-list { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
.photo-preview-item { position: relative; width: 80px; height: 80px; flex-shrink: 0; }
.photo-preview-thumb { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
.photo-remove { position: absolute; top: 2px; right: 2px; width: 22px; height: 22px; border: none; border-radius: 50%; background: #f45c43; color: white; cursor: pointer; font-size: 1rem; line-height: 1; padding: 0; display: flex; align-items: center; justify-content: center; }
.camera-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; }
.camera-modal-content { background: #fff; border-radius: 12px; overflow: hidden; max-width: 100%; }
.camera-modal-content video { display: block; width: 100%; max-height: 70vh; }
.camera-modal-actions { display: flex; gap: 0.5rem; padding: 1rem; justify-content: center; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('ambienteAtual').addEventListener('change', function() {
    document.getElementById('localizacaoHidden').value = this.value || '';
});

const categoriaSelect = new TomSelect('#categoria', {
    create: true,
    sortField: 'text'
});

const marcaSelect = new TomSelect('#marca_modelo', {
    create: true,
    sortField: 'text'
});

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
    }
    this.value = '';
});

var cameraStream = null;
var cameraVideo = document.getElementById('cameraVideo');
var cameraModal = document.getElementById('cameraModal');

document.getElementById('btnCamera').addEventListener('click', function() {
    cameraModal.style.display = 'flex';
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(function(stream) {
        cameraStream = stream;
        cameraVideo.srcObject = stream;
    }).catch(function(err) {
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível acessar a câmera.' });
        cameraModal.style.display = 'none';
    });
});

document.getElementById('btnCloseCamera').addEventListener('click', function() {
    if (cameraStream) cameraStream.getTracks().forEach(function(t) { t.stop(); });
    cameraStream = null;
    cameraVideo.srcObject = null;
    cameraModal.style.display = 'none';
});

document.getElementById('btnCapture').addEventListener('click', function() {
    var canvas = document.createElement('canvas');
    canvas.width = cameraVideo.videoWidth;
    canvas.height = cameraVideo.videoHeight;
    canvas.getContext('2d').drawImage(cameraVideo, 0, 0);
    canvas.toBlob(function(blob) {
        var file = new File([blob], 'captura-' + Date.now() + '.jpg', { type: 'image/jpeg' });
        selectedPhotos.push(file);
        renderPreviews();
    }, 'image/jpeg', 0.9);
});

document.getElementById('itemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const ambiente = document.getElementById('ambienteAtual').value;
    if (!ambiente) {
        Swal.fire({
            icon: 'warning',
            title: 'Ambiente obrigatório',
            text: 'Selecione o ambiente antes de adicionar o item.'
        });
        return;
    }
    
    document.getElementById('localizacaoHidden').value = ambiente;
    
    var formData = new FormData(this);
    selectedPhotos.forEach(function(file) {
        formData.append('fotos[]', file);
    });
    var submitBtn = document.getElementById('btnAddItem');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Salvando...';
    
    try {
        var response = await fetch('{{ route("inspections.items.store", $inspection) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        var data = await response.json().catch(function() { return {}; });
        
        if (response.ok && data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: data.message,
                showConfirmButton: true,
                confirmButtonText: 'Ok',
                confirmButtonColor: '#667eea'
            }).then(function() {
                window.location.reload();
            });
        } else {
            const msg = (data.errors ? Object.values(data.errors).flat().join('\n') : null) || data.message || 'Erro ao adicionar item';
            throw new Error(msg);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro ao adicionar item',
            text: error.message || 'Erro ao adicionar item'
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

async function deleteItem(id) {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover este item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f45c43',
        cancelButtonColor: '#667eea',
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
                    confirmButtonColor: '#667eea'
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
</script>
@endpush
@endsection
