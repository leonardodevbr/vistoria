@extends('layouts.app')

@section('content')
<div class="card">
    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm btn-icon" style="width: auto; margin-bottom: 1rem;">
        <i data-lucide="arrow-left" width="16" height="16"></i> Voltar
    </a>

    <h2 class="icon-wrap page-title"><i data-lucide="user-plus" width="20" height="20"></i> Novo usuário</h2>
    <p class="page-subtitle">Crie um usuário para acessar o sistema (nome, login e senha).</p>

    <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Nome *</label>
            <input type="text" name="name" class="form-control" required
                   value="{{ old('name') }}" placeholder="Ex: João Silva">
        </div>
        <div class="form-group">
            <label class="form-label">Login (usuário) *</label>
            <input type="text" name="username" class="form-control" required autocomplete="off"
                   value="{{ old('username') }}" placeholder="Usado para entrar no sistema">
        </div>
        <div class="form-group">
            <label class="form-label">E-mail *</label>
            <input type="email" name="email" class="form-control" required
                   value="{{ old('email') }}" placeholder="email@exemplo.com">
        </div>
        <div class="form-group">
            <label class="form-label">Senha *</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres">
        </div>
        <div class="form-group">
            <label class="form-label">Confirmar senha *</label>
            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password"
                   placeholder="Repita a senha">
        </div>

        <button type="submit" class="btn btn-success btn-icon">
            <i data-lucide="check" width="18" height="18"></i> Criar usuário
        </button>
    </form>
</div>
@endsection
