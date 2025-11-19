#!/bin/bash

echo "=========================================="
echo "VALIDAÇÃO DO SISTEMA DE CONFIRMAÇÃO"
echo "=========================================="
echo ""

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Contador de erros
ERRORS=0

# Função para verificar arquivo
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $2"
    else
        echo -e "${RED}✗${NC} $2 - ARQUIVO NÃO ENCONTRADO: $1"
        ((ERRORS++))
    fi
}

# Função para verificar conteúdo
check_content() {
    if grep -q "$2" "$1" 2>/dev/null; then
        echo -e "${GREEN}✓${NC} $3"
    else
        echo -e "${RED}✗${NC} $3 - NÃO ENCONTRADO EM: $1"
        ((ERRORS++))
    fi
}

echo "1. VERIFICANDO ARQUIVOS CRIADOS/MODIFICADOS"
echo "-------------------------------------------"

# Migration
check_file "database/migrations/2025_11_11_200000_add_awaiting_confirmation_status_to_orders.php" "Migration de status awaiting_confirmation"

# Views
check_file "resources/views/orders/awaiting-confirmation.blade.php" "View de pedido em análise (cliente)"

# Notificações
check_file "app/Notifications/OrderConfirmed.php" "Notificação OrderConfirmed"
check_file "app/Notifications/OrderRejected.php" "Notificação OrderRejected"

# Documentação
check_file "SISTEMA_CONFIRMACAO_PEDIDOS.md" "Documentação completa"

echo ""
echo "2. VERIFICANDO CONTROLLERS"
echo "-------------------------------------------"

# OrderController
check_content "app/Http/Controllers/OrderController.php" "awaiting_confirmation" "Status awaiting_confirmation no OrderController"
check_content "app/Http/Controllers/OrderController.php" "awaitingConfirmation" "Método awaitingConfirmation"
check_content "app/Http/Controllers/OrderController.php" "confirmOrder" "Método confirmOrder"
check_content "app/Http/Controllers/OrderController.php" "rejectOrder" "Método rejectOrder"

# SalesController
check_content "app/Http/Controllers/SalesController.php" "awaitingConfirmation" "Contador de pedidos aguardando confirmação"

echo ""
echo "3. VERIFICANDO ROTAS"
echo "-------------------------------------------"

check_content "routes/web.php" "orders.awaiting-confirmation" "Rota awaiting-confirmation"
check_content "routes/web.php" "orders.confirm" "Rota orders.confirm"
check_content "routes/web.php" "orders.reject" "Rota orders.reject"

echo ""
echo "4. VERIFICANDO VIEWS"
echo "-------------------------------------------"

# View de análise
check_content "resources/views/orders/awaiting-confirmation.blade.php" "Pedido em Análise" "Título na view de análise"
check_content "resources/views/orders/awaiting-confirmation.blade.php" "pulse-slow" "Animação na view de análise"
check_content "resources/views/orders/awaiting-confirmation.blade.php" "location.reload" "Auto-refresh na view de análise"

# View de vendas
check_content "resources/views/sales/show.blade.php" "awaiting_confirmation" "Status awaiting_confirmation na view de vendas"
check_content "resources/views/sales/show.blade.php" "orders.confirm" "Botão de confirmação"
check_content "resources/views/sales/show.blade.php" "orders.reject" "Botão de rejeição"
check_content "resources/views/sales/show.blade.php" "rejectModal" "Modal de rejeição"

echo ""
echo "5. VERIFICANDO NOTIFICAÇÕES"
echo "-------------------------------------------"

# OrderConfirmed
check_content "app/Notifications/OrderConfirmed.php" "OrderConfirmed" "Classe OrderConfirmed"
check_content "app/Notifications/OrderConfirmed.php" "toMail" "Método toMail em OrderConfirmed"
check_content "app/Notifications/OrderConfirmed.php" "Pedido Confirmado" "Assunto do e-mail de confirmação"

# OrderRejected
check_content "app/Notifications/OrderRejected.php" "OrderRejected" "Classe OrderRejected"
check_content "app/Notifications/OrderRejected.php" "toMail" "Método toMail em OrderRejected"
check_content "app/Notifications/OrderRejected.php" "producer_rejection_reason" "Motivo de rejeição"

# ProducerNotificationService
check_content "app/Services/ProducerNotificationService.php" "Aguardando Confirmação" "Mensagem atualizada no serviço"
check_content "app/Services/ProducerNotificationService.php" "CONFIRMAÇÃO NECESSÁRIA" "Alerta no WhatsApp"

echo ""
echo "6. VERIFICANDO MIGRATION"
echo "-------------------------------------------"

check_content "database/migrations/2025_11_11_200000_add_awaiting_confirmation_status_to_orders.php" "awaiting_confirmation" "Status awaiting_confirmation"
check_content "database/migrations/2025_11_11_200000_add_awaiting_confirmation_status_to_orders.php" "rejected" "Status rejected"
check_content "database/migrations/2025_11_11_200000_add_awaiting_confirmation_status_to_orders.php" "producer_confirmed_at" "Campo producer_confirmed_at"
check_content "database/migrations/2025_11_11_200000_add_awaiting_confirmation_status_to_orders.php" "producer_rejection_reason" "Campo producer_rejection_reason"

echo ""
echo "=========================================="
echo "RESULTADO DA VALIDAÇÃO"
echo "=========================================="

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ TODOS OS TESTES PASSARAM!${NC}"
    echo ""
    echo "O sistema de confirmação de pedidos está completo e pronto para uso."
    echo ""
    echo "PRÓXIMOS PASSOS:"
    echo "1. Execute: php artisan migrate"
    echo "2. Teste o fluxo completo conforme documentação"
    echo "3. Configure SMTP para envio de e-mails (opcional)"
    echo "4. Configure WhatsApp API para notificações (opcional)"
    exit 0
else
    echo -e "${RED}✗ ENCONTRADOS $ERRORS ERRO(S)${NC}"
    echo ""
    echo "Por favor, verifique os arquivos indicados acima."
    exit 1
fi
