# 🏠 Sistema de Vistoria de Imóveis

Sistema simples e prático para vistoria de imóveis, desenvolvido em Laravel com foco em dispositivos móveis.

## ✨ Funcionalidades

- 📋 Criar e gerenciar múltiplas vistorias
- ➕ Adicionar itens com categorização automática
- 📷 Captura de fotos diretamente do celular
- 📊 Campos padronizados para estado físico e funcionamento
- 🔍 Selects pesquisáveis com TomSelect (marcas, categorias, localizações)
- 📄 Geração de PDF detalhado e organizado
- 🔒 Proteção por senha simples
- 📱 Interface mobile-first responsiva

## 🚀 Instalação

### Pré-requisitos

- PHP 8.2 ou superior
- MySQL 5.7 ou superior
- Composer

### Passo a Passo

1. **Criar o banco de dados MySQL**

```bash
mysql -u root
CREATE DATABASE vistoria_imovel;
EXIT;
```

Se sua instalação MySQL tem senha:
```bash
mysql -u root -p
```

2. **Executar as migrations**

```bash
php artisan migrate
```

3. **Iniciar o servidor**

```bash
php artisan serve
```

4. **Acessar o sistema**

Abra o navegador em: `http://localhost:8000`

**Senha padrão:** `vistoria2024`

## 🔧 Configuração

### Alterar a senha de acesso

Edite o arquivo `routes/web.php` e altere a variável `$senha` nas rotas de login:

```php
$senha = 'SUA_NOVA_SENHA_AQUI';
```

### Alterar configurações do banco

Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vistoria_imovel
DB_USERNAME=root
DB_PASSWORD=
```

## 📱 Uso Mobile

O sistema foi desenvolvido com foco em dispositivos móveis:

- ✅ Layout otimizado para telas pequenas
- ✅ Botões grandes e fáceis de tocar
- ✅ Captura de foto direto da câmera
- ✅ Campos pesquisáveis para preenchimento rápido
- ✅ Interface intuitiva e rápida

## 🎨 Categorias Pré-configuradas

- Eletrodomésticos
- Móveis
- Planejados
- Piso
- Banheiro
- Cozinha
- Quartos
- Sala
- Área de Serviço

## 📊 Campos de Vistoria

### Obrigatórios
- Item
- Localização
- Estado físico
- Funcionamento

### Opcionais
- Categoria
- Marca/Modelo
- Observações
- Foto

### Estados Físicos
- Novo
- Seminovo
- Ótimo
- Bom
- Regular

### Funcionamento
- Funcionando perfeitamente
- Funcionando
- Funcionando com ressalvas
- Não testado
- Não funciona
- Não se aplica

## 📄 PDF Gerado

O PDF inclui:
- ✅ Informações da vistoria (endereço, responsável, data)
- ✅ Resumo por categoria
- ✅ Todos os itens agrupados por categoria
- ✅ Fotos dos itens (quando disponíveis)
- ✅ Detalhes completos de cada item
- ✅ Layout profissional e organizado

## 🌐 Hospedagem Online

### Recomendações de Hosting

**Opções gratuitas/baratas:**
- [InfinityFree](https://infinityfree.net/) - Hospedagem gratuita com MySQL
- [000webhost](https://www.000webhost.com/) - Hospedagem gratuita
- [Hostinger](https://www.hostinger.com.br/) - Planos a partir de R$ 6,99/mês

**Opções profissionais:**
- [DigitalOcean](https://www.digitalocean.com/) - $4/mês
- [Vultr](https://www.vultr.com/) - $2.50/mês
- [AWS Lightsail](https://aws.amazon.com/pt/lightsail/) - $3.50/mês

### Deploy em Hospedagem Compartilhada

1. Faça upload dos arquivos via FTP
2. Configure o banco de dados MySQL no painel
3. Ajuste o `.env` com as credenciais do banco
4. Execute `composer install --optimize-autoloader --no-dev`
5. Execute `php artisan migrate`
6. Execute `php artisan storage:link`
7. Configure o document root para a pasta `public`

### Deploy em VPS/Cloud

```bash
# Clonar repositório
git clone [seu-repositorio]
cd vistoria-imovel

# Instalar dependências
composer install --optimize-autoloader --no-dev

# Configurar .env
cp .env.example .env
php artisan key:generate

# Ajustar permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Executar migrations
php artisan migrate --force

# Link do storage
php artisan storage:link

# Configurar Nginx/Apache para apontar para /public
```

## 🛠️ Manutenção

### Limpar cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Backup do banco
```bash
mysqldump -u root vistoria_imovel > backup.sql
```

### Restaurar backup
```bash
mysql -u root vistoria_imovel < backup.sql
```

## 📝 Notas Técnicas

- **Framework:** Laravel 12
- **Biblioteca PDF:** DomPDF
- **Select Pesquisável:** TomSelect
- **Alertas:** SweetAlert2
- **Banco de Dados:** MySQL
- **Upload:** Storage local (public disk)

## 🔐 Segurança

⚠️ **IMPORTANTE:** Este sistema usa autenticação simples por senha. Para uso em produção, considere:

- Implementar autenticação completa com Laravel Breeze/Fortify
- Usar HTTPS (SSL)
- Implementar rate limiting
- Adicionar logs de auditoria
- Fazer backups regulares

## 📞 Suporte

Este é um sistema simples e direto. Para alterações:

- Rotas: `routes/web.php`
- Controllers: `app/Http/Controllers/InspectionController.php`
- Views: `resources/views/inspections/`
- Models: `app/Models/`
- Migrations: `database/migrations/`

## 📜 Licença

Este projeto é de uso livre para fins comerciais e pessoais.
