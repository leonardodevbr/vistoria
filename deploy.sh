#!/bin/bash

echo "🚀 Script de Deploy - Sistema de Vistoria de Imóveis"
echo "=================================================="
echo ""

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Instalar dependências
echo -e "${YELLOW}📦 Instalando dependências...${NC}"
composer install --optimize-autoloader --no-dev
echo -e "${GREEN}✅ Dependências instaladas!${NC}"
echo ""

# 2. Configurar .env
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚙️  Criando arquivo .env...${NC}"
    cp .env.example .env
    php artisan key:generate
    echo -e "${GREEN}✅ Arquivo .env criado!${NC}"
    echo -e "${YELLOW}⚠️  ATENÇÃO: Configure suas credenciais do banco de dados no .env${NC}"
    echo ""
else
    echo -e "${GREEN}✅ Arquivo .env já existe!${NC}"
    echo ""
fi

# 3. Configurar permissões
echo -e "${YELLOW}🔐 Configurando permissões...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permissões configuradas!${NC}"
echo ""

# 4. Criar link do storage
echo -e "${YELLOW}🔗 Criando link do storage...${NC}"
php artisan storage:link
echo -e "${GREEN}✅ Link do storage criado!${NC}"
echo ""

# 5. Limpar cache
echo -e "${YELLOW}🧹 Limpando cache...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✅ Cache limpo!${NC}"
echo ""

# 6. Executar migrations
echo -e "${YELLOW}📊 Deseja executar as migrations? (s/n)${NC}"
read -r response
if [[ "$response" =~ ^([sS][iI][mM]|[sS])$ ]]; then
    php artisan migrate --force
    echo -e "${GREEN}✅ Migrations executadas!${NC}"
else
    echo -e "${YELLOW}⏭️  Migrations ignoradas${NC}"
fi
echo ""

echo -e "${GREEN}=================================================="
echo "✅ Deploy concluído com sucesso!"
echo "=================================================="
echo ""
echo "🌐 Próximos passos:"
echo "1. Configure o .env com suas credenciais de banco"
echo "2. Configure o web server (Apache/Nginx) para apontar para /public"
echo "3. Execute as migrations se ainda não o fez"
echo "4. Acesse o sistema e faça login com a senha: vistoria2024"
echo ""
echo "📝 Para alterar a senha, edite o arquivo routes/web.php"
echo "${NC}"
