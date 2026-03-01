@extends('layouts.app')

@section('content')
<div class="card">
    <a href="{{ route('inspections.index') }}" class="btn btn-primary btn-sm btn-icon" style="width: auto; margin-bottom: 1rem;">
        <i data-lucide="arrow-left" width="16" height="16"></i> Voltar
    </a>
    
    <h2 class="icon-wrap page-title"><i data-lucide="file-text" width="20" height="20"></i> Vistoria #{{ $inspection->id }}</h2>
    <p class="page-subtitle">Dados do imóvel</p>
    
    <form action="{{ route('inspections.update', $inspection) }}" method="POST" id="inspectionForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="home" width="14" height="14"></i> Imóvel vistoriado</label>
            <input type="text" name="endereco" class="form-control" 
                   value="{{ old('endereco', $inspection->endereco) }}" 
                   placeholder="Ex: Apto 101, Casa 2">
        </div>
        
        <div class="form-section-label icon-wrap"><i data-lucide="map-pin" width="14" height="14"></i> Endereço</div>
        
        <div class="form-group form-group-cep">
            <label class="form-label">CEP</label>
            <div class="input-group-cep">
                <input type="text" name="cep" id="cep" class="input-group-cep-input" 
                       value="{{ old('cep', $inspection->cep_formatado) }}" 
                       placeholder="00000-000" maxlength="9" autocomplete="postal-code">
                <button type="button" id="btnBuscarCep" class="input-group-cep-btn">Buscar</button>
            </div>
            <span id="cepStatus" class="field-hint"></span>
        </div>
        
        <div class="form-group">
            <label class="form-label">Logradouro</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" 
                   value="{{ old('logradouro', $inspection->logradouro) }}" placeholder="Rua, avenida...">
        </div>
        
        <div class="form-row form-row-inline">
            <div class="form-group form-group-numero">
                <label class="form-label">Número</label>
                <input type="text" name="numero" id="numero" class="form-control" 
                       value="{{ old('numero', $inspection->numero) }}" placeholder="Nº">
            </div>
            <div class="form-group form-group-complemento">
                <label class="form-label">Complemento</label>
                <input type="text" name="complemento" id="complemento" class="form-control" 
                       value="{{ old('complemento', $inspection->complemento) }}" placeholder="Apto, bloco...">
            </div>
        </div>
        
        <div class="form-row form-row-inline">
            <div class="form-group form-group-bairro">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro" class="form-control" 
                       value="{{ old('bairro', $inspection->bairro) }}">
            </div>
            <div class="form-group form-group-cidade">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade" class="form-control" 
                       value="{{ old('cidade', $inspection->cidade) }}">
            </div>
            <div class="form-group form-group-uf">
                <label class="form-label">UF</label>
                <input type="text" name="uf" id="uf" class="form-control" 
                       value="{{ old('uf', $inspection->uf) }}" placeholder="SP" maxlength="2">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="user" width="14" height="14"></i> Responsável</label>
            <input type="text" name="responsavel" class="form-control" 
                   value="{{ old('responsavel', $inspection->responsavel) }}" placeholder="Nome">
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="user-check" width="14" height="14"></i> Locatário</label>
            <input type="text" name="locatario_nome" class="form-control" 
                   value="{{ old('locatario_nome', $inspection->locatario_nome) }}" placeholder="Nome do locatário">
        </div>
        
        <div class="form-group">
            <label class="form-label icon-wrap"><i data-lucide="calendar" width="14" height="14"></i> Data da vistoria</label>
            <input type="text" name="data_vistoria" id="data_vistoria" class="form-control input-date-time" readonly
                   value="{{ $inspection->data_vistoria?->format('Y-m-d H:i') }}"
                   placeholder="Clique para selecionar data e hora"
                   data-initial="{{ $inspection->data_vistoria?->format('Y-m-d H:i') }}">
        </div>
        
        <button type="submit" class="btn btn-success btn-icon">
            <i data-lucide="arrow-right" width="18" height="18"></i> Prosseguir
        </button>
    </form>
</div>

<div style="margin-top: 1rem;">
    @if($inspection->items->count() > 0)
        <a href="{{ route('inspections.items', $inspection) }}" class="btn btn-primary btn-icon">
            <i data-lucide="list" width="18" height="18"></i> Itens ({{ $inspection->items->count() }})
        </a>
    @endif
</div>

@push('styles')
<style>
.form-section-label { font-weight: 600; color: #555; margin-bottom: 0.5rem; font-size: 0.95rem; }
.form-row-inline { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.form-row-inline .form-group { min-width: 0; }
.form-row-inline .form-group-numero { width: 5rem; flex-shrink: 0; }
.form-row-inline .form-group-complemento { flex: 1; min-width: 100px; }
.form-row-inline .form-group-bairro { flex: 1; min-width: 100px; }
.form-row-inline .form-group-cidade { flex: 1; min-width: 100px; }
.form-row-inline .form-group-uf { width: 3.5rem; flex-shrink: 0; }
.form-group-cep { width: 100%; max-width: 240px; }
@media (max-width: 767px) {
    .form-group-cep { max-width: none; width: 100%; }
    .input-group-cep { width: 100%; }
    .input-group-cep-input { flex: 1; min-width: 0; width: auto; }
}
.input-group-cep { display: flex; align-items: stretch; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; transition: border-color 0.3s; }
.input-group-cep:focus-within { border-color: #667eea; outline: none; }
.input-group-cep-input { flex: 1; min-width: 0; width: 7rem; padding: 0.75rem 0.75rem; font-size: 1rem; border: none; background: #fff; }
.input-group-cep-input:focus { outline: none; }
.input-group-cep-btn { flex-shrink: 0; padding: 0 0.875rem; font-size: 0.85rem; font-weight: 600; border: none; border-left: 2px solid #e0e0e0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; white-space: nowrap; }
.input-group-cep-btn:hover { opacity: 0.95; }
.input-group-cep-btn:disabled { opacity: 0.7; cursor: not-allowed; }
.field-hint { font-size: 0.8rem; color: #666; margin-top: 0.25rem; display: block; }
.input-date-time { cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr === 'undefined') return;
    var dataInput = document.getElementById('data_vistoria');
    if (!dataInput) return;
    var initial = dataInput.getAttribute('data-initial') || dataInput.value || '';
    flatpickr(dataInput, {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        altInput: true,
        altFormat: 'd/m/Y H:i',
        time_24hr: true,
        locale: 'pt',
        defaultDate: initial || undefined,
        allowInput: false,
        clickOpens: true
    });
});
</script>
<script>
(function() {
    var cepInput = document.getElementById('cep');
    var btnBuscar = document.getElementById('btnBuscarCep');
    var cepStatus = document.getElementById('cepStatus');

    function formatCep(v) {
        v = v.replace(/\D/g, '');
        if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5);
        return v.slice(0, 9);
    }
    cepInput.addEventListener('input', function() {
        this.value = formatCep(this.value);
    });

    function buscarCep() {
        var cep = (document.getElementById('cep').value || '').replace(/\D/g, '');
        if (cep.length !== 8) {
            cepStatus.textContent = 'Informe um CEP com 8 dígitos.';
            return;
        }
        cepStatus.textContent = 'Buscando...';
        btnBuscar.disabled = true;

        function preencher(d) {
            if (d && (d.logradouro || d.street)) {
                document.getElementById('logradouro').value = d.logradouro || d.street || '';
                document.getElementById('bairro').value = d.bairro || d.district || d.neighborhood || '';
                document.getElementById('cidade').value = d.localidade || d.city || '';
                document.getElementById('uf').value = (d.uf || d.state || '').substring(0, 2).toUpperCase();
                cepStatus.textContent = 'Endereço preenchido.';
            } else {
                cepStatus.textContent = 'CEP não encontrado.';
            }
        }

        fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.erro) {
                    return fetch('https://opencep.com/v1/' + cep).then(function(r) { return r.json(); })
                        .then(function(d) { preencher(d); })
                        .catch(function() { preencher(null); });
                }
                preencher(data);
            })
            .catch(function() {
                return fetch('https://opencep.com/v1/' + cep)
                    .then(function(r) { return r.json(); })
                    .then(function(d) { preencher(d); })
                    .catch(function() {
                        cepStatus.textContent = 'Erro ao buscar CEP.';
                        preencher(null);
                    });
            })
            .finally(function() { btnBuscar.disabled = false; });
    }

    btnBuscar.addEventListener('click', buscarCep);
    cepInput.addEventListener('blur', function() {
        if ((this.value || '').replace(/\D/g, '').length === 8) buscarCep();
    });
})();
</script>
@endpush
@endsection
