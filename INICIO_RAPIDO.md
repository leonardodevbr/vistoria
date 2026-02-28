# 🚀 Início Rápido

## ✅ Sistema Instalado com Sucesso!

O servidor está rodando em: **http://localhost:8000**

### 🔐 Acesso

- **URL:** http://localhost:8000
- **Senha:** `vistoria2024`

---

## 📱 Como Usar

### 1. Fazer Login
- Acesse http://localhost:8000
- Digite a senha: `vistoria2024`
- Clique em "Entrar"

### 2. Criar uma Nova Vistoria
- Clique em "➕ Nova Vistoria"
- Preencha o endereço e nome do responsável (opcional)
- Clique em "💾 Salvar Informações"

### 3. Adicionar Itens
- Preencha o formulário de item
- Use os campos pesquisáveis (Categoria, Marca/Modelo, Localização)
- Tire uma foto se necessário
- Clique em "✅ Adicionar Item"

### 4. Gerar PDF
- Após adicionar todos os itens
- Clique em "📄 Gerar PDF da Vistoria"
- O PDF será baixado automaticamente

---

## 🛠️ Comandos Úteis

### Iniciar o Servidor
```bash
php artisan serve
```

### Criar o Banco de Dados (se ainda não criou)
```bash
mysql -u root < database/create_database.sql
```

### Executar Migrations
```bash
php artisan migrate
```

### Criar Link do Storage (para fotos)
```bash
php artisan storage:link
```

### Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 🔧 Alterar Senha

Para alterar a senha de acesso, edite o arquivo `routes/web.php`:

```php
// Linha 8 e linha 13
$senha = 'SUA_NOVA_SENHA';
```

---

## 📊 Estrutura do Banco

- **inspections** - Vistorias
  - id
  - endereco
  - responsavel
  - data_vistoria
  - timestamps

- **inspection_items** - Itens da vistoria
  - id
  - inspection_id (foreign key)
  - categoria
  - item
  - marca_modelo
  - localizacao
  - estado_fisico
  - funcionamento
  - observacoes
  - foto
  - timestamps

---

## 🌐 Para Hospedar Online

1. **Escolha um serviço de hospedagem:**
   - InfinityFree (gratuito)
   - Hostinger (R$ 6,99/mês)
   - DigitalOcean ($4/mês)

2. **Faça upload dos arquivos**

3. **Configure o banco de dados no .env**

4. **Execute os comandos:**
```bash
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan storage:link
```

---

## ❓ Problemas Comuns

### Erro de conexão com banco
- Verifique se o MySQL está rodando
- Verifique as credenciais no `.env`

### Fotos não aparecem
- Execute: `php artisan storage:link`
- Verifique permissões da pasta storage

### Erro 500
- Execute: `composer install`
- Execute: `php artisan key:generate`
- Verifique permissões das pastas storage e bootstrap/cache

---

## 📞 Estrutura de Arquivos

```
vistoria-imovel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── InspectionController.php
│   │   └── Middleware/
│   │       └── SimplePasswordMiddleware.php
│   └── Models/
│       ├── Inspection.php
│       └── InspectionItem.php
├── database/
│   └── migrations/
│       ├── 2026_02_28_194009_create_inspections_table.php
│       └── 2026_02_28_194010_create_inspection_items_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── inspections/
│       │   ├── index.blade.php
│       │   ├── form.blade.php
│       │   └── pdf.blade.php
│       └── login.blade.php
├── routes/
│   └── web.php
├── .env
└── README.md
```

---

## ✨ Pronto para Usar!

Acesse agora: **http://localhost:8000**

Qualquer dúvida, consulte o README.md completo.
