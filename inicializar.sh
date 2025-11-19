#!/bin/bash

echo "=== INICIALIZANDO AGROPERTO ==="
echo ""

# Verificar se MySQL está rodando
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL não encontrado. Instalando..."
    # Para Ubuntu/Debian
    if command -v apt &> /dev/null; then
        sudo apt update
        sudo apt install -y mysql-server
        sudo systemctl start mysql
        sudo systemctl enable mysql
    fi
fi

# Criar banco de dados
echo "📊 Criando banco de dados..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS agroperto;" 2>/dev/null || {
    echo "⚠️  Não foi possível conectar ao MySQL com usuário root sem senha."
    echo "   Configure o MySQL manualmente ou use as credenciais corretas no .env"
}

# Executar migrações
echo "🔄 Executando migrações..."
php artisan migrate --force

# Executar seeders
echo "🌱 Populando banco com dados iniciais..."
php artisan db:seed --force

# Limpar caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "✅ AGROPERTO INICIALIZADO COM SUCESSO!"
echo ""
echo "🚀 Para iniciar o servidor:"
echo "   php artisan serve"
echo ""
echo "🌐 Acesse: http://localhost:8000"
echo ""
echo "👥 Usuários de teste:"
echo "   Produtor: joao.produtor@teste.com / 123456789"
echo "   Consumidor: maria.consumidor@teste.com / 123456789"
echo ""
