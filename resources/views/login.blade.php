<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Vistoria de Imóvel</title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
        }
        
        .login-container {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        
        .logo-img {
            max-width: 140px;
            height: auto;
            margin-bottom: 0.75rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        h1 {
            text-align: center;
            color: #333;
            font-size: 1.35rem;
            margin-bottom: 0.25rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .btn {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.85;
            transform: none;
        }
        
        .btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .btn-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: btn-spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 0.4rem;
        }
        
        @keyframes btn-spin {
            to { transform: rotate(360deg); }
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .icon-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('logo.png') }}" alt="Vistoria de Imóvel" class="logo-img">
            <h1>Vistoria de Imóvel</h1>
            <p class="subtitle">Sistema de Vistoria</p>
        </div>
        
        @if(session('erro'))
            <div class="alert alert-danger">
                {{ session('erro') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label icon-wrap"><i data-lucide="user" width="14" height="14"></i> Usuário</label>
                <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    placeholder="Nome de usuário"
                    value="{{ old('username') }}"
                    autofocus
                    required>
            </div>
            
            <div class="form-group">
                <label class="form-label icon-wrap"><i data-lucide="lock" width="14" height="14"></i> Senha</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Senha"
                    required>
            </div>
            
            <button type="submit" class="btn btn-icon" id="btnLogin">
                <i data-lucide="log-in" width="18" height="18"></i> Entrar
            </button>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        var form = document.getElementById('loginForm');
        var btn = document.getElementById('btnLogin');
        if (form && btn) {
            form.addEventListener('submit', function() {
                if (btn.disabled) return;
                btn.disabled = true;
                btn.innerHTML = '<span class="btn-spinner"></span> Entrando...';
            });
        }
    });
    </script>
</body>
</html>
