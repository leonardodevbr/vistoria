@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1rem;">📋 Minhas Vistorias</h2>
    
    <form action="{{ route('inspections.create') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            ➕ Nova Vistoria
        </button>
    </form>
</div>

@if($inspections->isEmpty())
    <div class="card" style="text-align: center; padding: 3rem 1.5rem;">
        <p style="font-size: 3rem; margin-bottom: 1rem;">📝</p>
        <p style="color: #999; font-size: 1.1rem;">Nenhuma vistoria criada ainda.</p>
        <p style="color: #999;">Clique em "Nova Vistoria" para começar!</p>
    </div>
@else
    @foreach($inspections as $inspection)
        <div class="card">
            <h3 style="color: #667eea; margin-bottom: 0.5rem;">
                Vistoria #{{ $inspection->id }}
            </h3>
            
            @if($inspection->endereco)
                <p style="margin-bottom: 0.5rem;">
                    <strong>📍 Endereço:</strong> {{ $inspection->endereco }}
                </p>
            @endif
            
            @if($inspection->responsavel)
                <p style="margin-bottom: 0.5rem;">
                    <strong>👤 Responsável:</strong> {{ $inspection->responsavel }}
                </p>
            @endif
            
            <p style="margin-bottom: 1rem;">
                <strong>📅 Data:</strong> {{ $inspection->data_vistoria->format('d/m/Y H:i') }}
            </p>
            
            <div style="margin-bottom: 1rem;">
                <span class="badge badge-info">
                    {{ $inspection->items->count() }} {{ $inspection->items->count() == 1 ? 'item' : 'itens' }}
                </span>
            </div>
            
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-primary btn-sm">
                    ✏️ Editar
                </a>
                
                @if($inspection->items->count() > 0)
                    <a href="{{ route('inspections.pdf', $inspection) }}" class="btn btn-success btn-sm">
                        📄 Gerar PDF
                    </a>
                @endif
                
                <button onclick="deleteInspection({{ $inspection->id }})" class="btn btn-danger btn-sm">
                    🗑️ Excluir
                </button>
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
        cancelButtonColor: '#667eea',
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
