@extends('layouts.app')

@section('content')
<div class="card">
    <h2 class="icon-wrap" style="margin-bottom: 1rem;"><i data-lucide="clipboard-list" width="22" height="22"></i> Minhas Vistorias</h2>
    
    <form action="{{ route('inspections.create') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary btn-icon">
            <i data-lucide="plus" width="18" height="18"></i> Nova Vistoria
        </button>
    </form>
</div>

@if($inspections->isEmpty())
    <div class="card" style="text-align: center; padding: 3rem 1.5rem;">
        <p style="color: #999; font-size: 1.1rem;">Nenhuma vistoria criada ainda.</p>
        <p style="color: #999;">Clique em "Nova Vistoria" para começar!</p>
    </div>
@else
    @foreach($inspections as $inspection)
        <div class="card">
            <h3 style="color: #334155; margin-bottom: 0.5rem;">
                Vistoria #{{ $inspection->id }}
            </h3>
            
            @if($inspection->endereco || $inspection->endereco_completo || $inspection->endereco_formatado)
                <p style="margin-bottom: 0.25rem;" class="icon-wrap">
                    <i data-lucide="map-pin" width="14" height="14"></i> <strong>Imóvel</strong>
                </p>
                <p style="margin-bottom: 0.5rem; padding-left: 1.5rem;">
                    @if($inspection->endereco){{ $inspection->endereco }}@endif
                    @if($inspection->endereco && ($inspection->endereco_formatado ?: $inspection->endereco_completo)) — @endif
                    @if($inspection->endereco_formatado){{ Str::limit($inspection->endereco_formatado, 50) }}@elseif($inspection->endereco_completo){{ Str::limit($inspection->endereco_completo, 50) }}@endif
                </p>
            @endif
            
            @if($inspection->responsavel)
                <p style="margin-bottom: 0.5rem;" class="icon-wrap">
                    <i data-lucide="user" width="14" height="14"></i> <strong>Responsável:</strong> {{ $inspection->responsavel }}
                </p>
            @endif
            
            <p style="margin-bottom: 1rem;" class="icon-wrap">
                <i data-lucide="calendar" width="14" height="14"></i> <strong>Data:</strong> {{ $inspection->data_vistoria->format('d/m/Y H:i') }}
            </p>
            
            @php $itensCadastrados = $inspection->items->filter(fn($i) => $i->is_draft !== true); @endphp
            <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                <span class="badge badge-info" style="background: #e2e8f0; color: #475569;">
                    {{ $itensCadastrados->count() }} {{ $itensCadastrados->count() == 1 ? 'item' : 'itens' }}
                </span>
                @if($inspection->isAprovado())
                    <span class="badge badge-success" style="background: #ccfbf1; color: #0f766e;">
                        <i data-lucide="lock" width="12" height="12" style="vertical-align: -2px;"></i> Aprovada
                    </span>
                @endif
            </div>
            
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                @if(!$inspection->isAprovado())
                    <a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-primary btn-sm btn-icon">
                        <i data-lucide="file-text" width="16" height="16"></i> Dados
                    </a>
                    <a href="{{ route('inspections.items', $inspection) }}" class="btn btn-outline btn-sm btn-icon">
                        <i data-lucide="list" width="16" height="16"></i> Itens
                    </a>
                    @if($itensCadastrados->count() > 0)
                        <form action="{{ route('inspections.approve', $inspection) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ao aprovar, a vistoria ficará selada e não poderá mais ser alterada. Confirma?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm btn-icon">
                                <i data-lucide="check-circle" width="16" height="16"></i> Aprovar
                            </button>
                        </form>
                    @endif
                    <button onclick="deleteInspection({{ $inspection->id }})" class="btn btn-outline-danger btn-sm btn-icon">
                        <i data-lucide="trash-2" width="16" height="16"></i> Excluir
                    </button>
                @endif
                @if($itensCadastrados->count() > 0)
                    <a href="{{ route('inspections.pdf', $inspection) }}" class="btn btn-outline btn-sm btn-icon">
                        <i data-lucide="file-down" width="16" height="16"></i> {{ $inspection->isAprovado() ? 'Baixar PDF' : 'Gerar PDF' }}
                    </a>
                @endif
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script>
function deleteInspection(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f45c43',
        cancelButtonColor: '#2563eb',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/inspections/${id}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
