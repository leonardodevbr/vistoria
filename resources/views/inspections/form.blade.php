@extends('layouts.app')

@section('content')
<div class="card">
    <a href="{{ route('inspections.index') }}" class="btn btn-primary btn-sm" style="width: auto; margin-bottom: 1rem;">
        ← Voltar
    </a>
    
    <h2 style="margin-bottom: 1rem;">Vistoria #{{ $inspection->id }}</h2>
    
    <form action="{{ route('inspections.update', $inspection) }}" method="POST" id="inspectionForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">📍 Endereço do Imóvel</label>
            <input type="text" name="endereco" class="form-control" 
                   value="{{ $inspection->endereco }}" 
                   placeholder="Ex: Rua das Flores, 123 - Apto 45">
        </div>
        
        <div class="form-group">
            <label class="form-label">👤 Responsável pela Vistoria</label>
            <input type="text" name="responsavel" class="form-control" 
                   value="{{ $inspection->responsavel }}" 
                   placeholder="Nome do corretor">
        </div>
        
        <button type="submit" class="btn btn-success">
            💾 Salvar Informações
        </button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem;">➕ Adicionar Item</h3>
    
    <form id="itemForm" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="form-label">📦 Categoria</label>
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
            <label class="form-label">🏷️ Item *</label>
            <input type="text" id="item" name="item" class="form-control" required
                   placeholder="Ex: Geladeira, Sofá, Armário...">
        </div>
        
        <div class="form-group">
            <label class="form-label">🔖 Marca/Modelo</label>
            <select id="marca_modelo" name="marca_modelo" class="form-control">
                <option value="">Selecione ou digite...</option>
                <option value="Brastemp">Brastemp</option>
                <option value="LG">LG</option>
                <option value="Samsung">Samsung</option>
                <option value="Electrolux">Electrolux</option>
                <option value="Consul">Consul</option>
                <option value="Philco">Philco</option>
                <option value="Midea">Midea</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">📍 Localização *</label>
            <select id="localizacao" name="localizacao" class="form-control" required>
                <option value="">Selecione ou digite...</option>
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
        
        <div class="form-group">
            <label class="form-label">⭐ Estado Físico *</label>
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
            <label class="form-label">⚙️ Funcionamento *</label>
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
            <label class="form-label">📝 Observações</label>
            <textarea name="observacoes" class="form-control" 
                      placeholder="Detalhe riscos, manchas, folgas, etc."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">📷 Foto</label>
            <div class="file-input-wrapper">
                <input type="file" id="foto" name="foto" accept="image/*" capture="environment">
                <label for="foto" class="file-input-label">
                    📸 Tirar/Selecionar Foto
                </label>
            </div>
            <div id="photoPreview" style="display: none; margin-top: 1rem;">
                <img id="previewImage" class="photo-preview" src="" alt="Preview">
            </div>
        </div>
        
        <button type="submit" class="btn btn-success">
            ✅ Adicionar Item
        </button>
    </form>
</div>

<div id="itemsList">
    @if($inspection->items->count() > 0)
        <div class="card">
            <h3 style="margin-bottom: 1rem;">📋 Itens Cadastrados ({{ $inspection->items->count() }})</h3>
            
            @foreach($inspection->items->groupBy('categoria') as $categoria => $items)
                <h4 style="color: #667eea; margin-top: 1.5rem; margin-bottom: 1rem;">
                    {{ $categoria ?: 'Sem Categoria' }}
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
                        
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                 class="photo-preview" 
                                 alt="Foto do item">
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
        
        <a href="{{ route('inspections.pdf', $inspection) }}" class="btn btn-success">
            📄 Gerar PDF da Vistoria
        </a>
    @else
        <div class="card" style="text-align: center; padding: 2rem;">
            <p style="font-size: 2rem; margin-bottom: 0.5rem;">📦</p>
            <p style="color: #999;">Nenhum item cadastrado ainda.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Inicializar TomSelect nos campos
const categoriaSelect = new TomSelect('#categoria', {
    create: true,
    sortField: 'text'
});

const marcaSelect = new TomSelect('#marca_modelo', {
    create: true,
    sortField: 'text'
});

const localizacaoSelect = new TomSelect('#localizacao', {
    create: true,
    sortField: 'text'
});

// Preview da foto
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Submeter formulário de item via AJAX
document.getElementById('itemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Salvando...';
    
    try {
        const response = await fetch('{{ route("inspections.items.store", $inspection) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Erro ao adicionar item');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
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
                    timer: 1500,
                    showConfirmButton: false
                });
                
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
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
