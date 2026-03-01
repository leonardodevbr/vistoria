@extends('layouts.app')

@section('content')
<div class="card">
    <a href="{{ route('inspections.index') }}" class="btn btn-primary btn-sm btn-icon" style="width: auto; margin-bottom: 1rem;">
        <i data-lucide="arrow-left" width="16" height="16"></i> Voltar
    </a>

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
        <h2 class="icon-wrap page-title" style="margin-bottom: 0;"><i data-lucide="users" width="22" height="22"></i> Usuários</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm btn-icon" style="width: auto;">
            <i data-lucide="user-plus" width="16" height="16"></i> Novo usuário
        </a>
    </div>
    <p class="page-subtitle">Gerencie os usuários que podem acessar o sistema. Apenas o administrador vê esta página.</p>
</div>

<div class="card">
    @if($users->isEmpty())
        <p style="color: #999;">Nenhum usuário cadastrado.</p>
    @else
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($users as $u)
                <li style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #eee; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <strong>{{ $u->name }}</strong>
                        <span style="color: #666; font-size: 0.9rem;"> — {{ $u->username }}</span>
                        @if($u->username === 'admin')
                            <span class="badge badge-info" style="margin-left: 0.5rem;">Admin</span>
                        @endif
                    </div>
                    <a href="{{ route('users.edit', $u) }}" class="btn btn-primary btn-sm btn-icon" style="width: auto;">
                        <i data-lucide="key" width="14" height="14"></i> Alterar senha
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
