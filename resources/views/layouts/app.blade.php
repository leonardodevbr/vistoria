<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vistoria de Imóvel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }
        
        .header {
            background: #fff;
            color: #1a1a1a;
            padding: 0.875rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-bottom: 1px solid #eee;
        }
        
        .header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .content {
            padding: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .item-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .item-card h4 {
            color: #1e40af;
            margin-bottom: 0.5rem;
        }
        
        .item-info {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .item-info strong {
            color: #555;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .photo-preview {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            margin-top: 0.5rem;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-input-label {
            display: block;
            padding: 0.75rem;
            background: #f8f9fa;
            border: 2px dashed #2563eb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #2563eb;
            font-weight: 600;
        }
        
        .file-input-label:hover {
            background: #e8ecff;
        }
        
        @media (min-width: 768px) {
            .container {
                max-width: 768px;
            }
            
            .btn {
                width: auto;
            }
        }
        
        .ts-wrapper {
            margin-bottom: 1rem;
        }
        
        .ts-control {
            border: 2px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .ts-control:focus {
            border-color: #2563eb !important;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .loading.show {
            display: block;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .delete-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #f45c43;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-icon, .link-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-icon svg, .link-icon svg, .icon-wrap svg {
            flex-shrink: 0;
        }
        .icon-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .page-subtitle {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .header-logo {
            height: 36px;
            width: auto;
            max-width: 120px;
            object-fit: contain;
            vertical-align: middle;
        }
        .header-logout {
            background: none;
            border: none;
            color: #555;
            cursor: pointer;
            font-size: 0.95rem;
            padding: 0.25rem 0;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .header-logout:hover {
            color: #1a1a1a;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .header-actions a {
            color: #555;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .header-actions a:hover {
            color: #1a1a1a;
        }
        .header-menu-wrap {
            position: relative;
        }
        .header-hamburger {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            color: #555;
            border-radius: 8px;
        }
        .header-hamburger:hover {
            background: rgba(0,0,0,0.06);
            color: #1a1a1a;
        }
        .header-hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: currentColor;
            border-radius: 1px;
        }
        .header-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 6px;
            min-width: 200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
            padding: 6px 0;
            z-index: 1000;
            display: none;
        }
        .header-dropdown.open {
            display: block;
        }
        .header-dropdown a,
        .header-dropdown .header-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 10px 14px;
            color: #555;
            text-decoration: none;
            font-size: 0.95rem;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }
        .header-dropdown a:hover,
        .header-dropdown .header-dropdown-item:hover {
            background: #f1f5f9;
            color: #1a1a1a;
        }
        .header-dropdown form {
            padding: 0;
            margin: 0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="header">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1 class="header-brand">
                    <img src="{{ asset('logo.png') }}" alt="Vistoria de Imóvel" class="header-logo">
                    <span>Vistoria de Imóvel</span>
                </h1>
                <div class="header-actions header-menu-wrap">
                    <button type="button" class="header-hamburger" id="headerHamburger" aria-label="Menu" aria-expanded="false" aria-haspopup="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="header-dropdown" id="headerDropdown" role="menu">
                        <a href="{{ route('profile.password') }}" role="menuitem">
                            <i data-lucide="lock" width="16" height="16"></i> Alterar senha
                        </a>
                        @if(auth()->user()->username === 'admin')
                            <a href="{{ route('users.index') }}" role="menuitem">
                                <i data-lucide="users" width="16" height="16"></i> Usuários
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" role="none">
                            @csrf
                            <button type="submit" class="header-dropdown-item" role="menuitem">
                                <i data-lucide="log-out" width="18" height="18"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="content">
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/pt.js"></script>
    <script>document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hamburger = document.getElementById('headerHamburger');
            var dropdown = document.getElementById('headerDropdown');
            if (!hamburger || !dropdown) return;
            function closeMenu() {
                dropdown.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
            function toggleMenu() {
                var isOpen = dropdown.classList.toggle('open');
                hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });
            document.addEventListener('click', function() { closeMenu(); });
            dropdown.addEventListener('click', function(e) { e.stopPropagation(); });
        });
    </script>
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
    @endif
    @if(session('erro') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{{ session('erro') ?? session('error') }}'
            });
        });
    </script>
    @endif
    
    @stack('scripts')
</body>
</html>
