# Início Rápido

## Sistema instalado

O servidor está rodando em: **http://localhost:8000**

### Acesso

- **URL:** http://localhost:8000
- **Login:** usuário e senha (após rodar `php artisan db:seed`, use usuário `admin` e senha `admin`)

---

## Como usar

### 1. Fazer login
- Acesse http://localhost:8000 (será redirecionado para /login se não estiver logado)
- Informe usuário e senha
- Clique em "Entrar"

### 2. Criar uma nova vistoria
- Clique em "Nova Vistoria"
- Preencha endereço (resumo e completo), responsável pela vistoria, nome do locatário e data
- Clique em "Salvar e continuar para itens"

### 3. Adicionar itens por ambiente
- Na tela de itens, selecione primeiro o **Ambiente** (ex.: Cozinha, Sala)
- Preencha categoria, item, marca/modelo, estado físico, funcionamento, observações e foto
- Clique em "Adicionar item"
- Repita para outros itens do mesmo ambiente; depois altere o ambiente e adicione os itens do próximo cômodo

### 4. Gerar PDF
- Após adicionar os itens, clique em "Gerar PDF da vistoria"
- O laudo em PDF será baixado com endereço completo e espaço para assinaturas (responsável e locatário)

---

## Comandos úteis

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

### Criar usuário inicial (primeira vez)

```bash
php artisan db:seed
```

Isso cria o usuário `admin` com senha `admin`. Altere a senha após o primeiro acesso (por exemplo via tinker ou criando tela de alteração de senha).

---

## Estrutura do banco

- **inspections** - Vistorias
  - id
  - endereco
  - endereco_completo
  - responsavel
  - locatario_nome
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

## Para hospedar online

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

## Problemas comuns

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

### Docker no Windows – erro 500 (tempnam / permissões)
Ao rodar o projeto em **Docker no Windows**, o volume montado fica com dono do host e o PHP no container (usuário `www-data`) não consegue escrever em `storage` e `bootstrap/cache`, gerando erro ao compilar views.

**Solução:** execute dentro do container, na pasta do projeto (ajuste o caminho e o nome do container se for o caso):

```bash
# Do host (PowerShell/WSL), com o container já rodando:
docker exec -it <nome_ou_id_do_container> bash -c "cd /home/dev/apps/vistoria && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```

Ou use o script do projeto (dentro do container, na pasta do app):

```bash
docker exec -it <nome_ou_id_do_container> bash -c "cd /home/dev/apps/vistoria && ./fix-permissions-docker.sh"
```

Depois de rodar uma vez, o erro 500 de "tempnam(): file created in the system's temporary directory" deve sumir. Se recriar o container ou o volume, rode de novo.

---

## Estrutura de arquivos

```
vistoria/
├── app/
│   ├── Http/Controllers/
│   │   └── InspectionController.php
│   └── Models/
│       ├── Inspection.php
│       └── InspectionItem.php
├── database/migrations/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── inspections/
│   │   ├── index.blade.php
│   │   ├── form.blade.php
│   │   ├── items.blade.php
│   │   └── pdf.blade.php
│   └── login.blade.php
├── routes/web.php
└── .env
```

---

## Pronto para usar

Acesse agora: **http://localhost:8000**

Qualquer dúvida, consulte o README.md completo.
