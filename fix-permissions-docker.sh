#!/bin/bash
# Corrige dono e permissões de storage e bootstrap/cache para o PHP no Docker.
# Execute DENTRO do container (ou via docker exec) na pasta do projeto.
# Uso: docker exec -it <container> bash -c "cd /home/dev/apps/vistoria && ./fix-permissions-docker.sh"

set -e

# Usuário que roda o PHP no container (ajuste se for outro, ex: apache, dev)
WEB_USER="${WEB_USER:-www-data}"

APP_ROOT="${1:-.}"
cd "$APP_ROOT"

echo "Ajustando permissões para $WEB_USER em $PWD ..."

# Garante que pastas existam
mkdir -p storage/framework/{sessions,views,cache/data,temp}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Dono: usuário do servidor web
chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache

# Permissões de escrita para o dono e grupo
chmod -R 775 storage bootstrap/cache

echo "Concluído."
