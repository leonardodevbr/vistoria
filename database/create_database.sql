-- Script para criar o banco de dados
-- Execute este script no MySQL antes de rodar as migrations

CREATE DATABASE IF NOT EXISTS vistoria_imovel 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Para usar o banco criado
USE vistoria_imovel;

-- Informações
SELECT 'Banco de dados criado com sucesso!' as status;
SELECT 'Agora execute: php artisan migrate' as proximo_passo;
