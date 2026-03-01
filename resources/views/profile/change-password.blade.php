@extends('layouts.app')

@section('content')
<div class="card">
    <a href="{{ route('inspections.index') }}" class="btn btn-primary btn-sm btn-icon" style="width: auto; margin-bottom: 1rem;">
        <i data-lucide="arrow-left" width="16" height="16"></i> Voltar
    </a>

    <h2 class="icon-wrap page-title"><i data-lucide="lock" width="20" height="20"></i> Alterar minha senha</h2>
    <p class="page-subtitle">Troque a senha de acesso ao sistema.</p>

    <form action="{{ route('profile.password.update') }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

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
            <label class="form-label">Senha atual *</label>
            <input type="password" name="current_password" class="form-control" required autocomplete="off"
                   placeholder="Digite sua senha atual">
        </div>
        <div class="form-group">
            <label class="form-label">Nova senha *</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres">
        </div>
        <div class="form-group">
            <label class="form-label">Confirmar nova senha *</label>
            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password"
                   placeholder="Repita a nova senha">
        </div>

        <button type="submit" class="btn btn-success btn-icon">
            <i data-lucide="check" width="18" height="18"></i> Salvar nova senha
        </button>
    </form>
</div>
@endsection
