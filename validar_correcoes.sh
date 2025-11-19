#!/bin/bash

echo "======================================"
echo "VALIDAÇÃO DAS CORREÇÕES - AGROPERTO"
echo "======================================"
echo ""

# Cores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Contador de erros
ERRORS=0

echo "1. Verificando correção do campo pickup_time..."
if grep -q 'value="{{ old(' resources/views/checkout/simple-index.blade.php | grep -q 'pickup_time'; then
    echo -e "${RED}❌ ERRO: Atributo 'value' ainda presente no select pickup_time${NC}"
    ERRORS=$((ERRORS + 1))
else
    echo -e "${GREEN}✅ Campo pickup_time corrigido (atributo value removido)${NC}"
fi

if grep -q "{{ old('pickup_time') == '08:00' ? 'selected' : '' }}" resources/views/checkout/simple-index.blade.php; then
    echo -e "${GREEN}✅ Atributo 'selected' adicionado corretamente nas opções${NC}"
else
    echo -e "${RED}❌ ERRO: Atributo 'selected' não encontrado nas opções${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "2. Verificando correção do método casts() no User.php..."
if grep -A 5 "protected function casts():" app/Models/User.php | grep -q "return \["; then
    echo -e "${GREEN}✅ Método casts() corrigido (array com colchete de abertura)${NC}"
else
    echo -e "${RED}❌ ERRO: Método casts() ainda com erro de sintaxe${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "3. Verificando campos de rádio payment_method..."
RADIO_COUNT=$(grep -c 'name="payment_method"' resources/views/checkout/simple-index.blade.php)
if [ "$RADIO_COUNT" -ge 2 ]; then
    echo -e "${GREEN}✅ Campos de rádio payment_method encontrados ($RADIO_COUNT campos)${NC}"
else
    echo -e "${RED}❌ ERRO: Campos de rádio payment_method não encontrados adequadamente${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "4. Verificando estrutura do formulário..."
if grep -q "action=\"{{ route('checkout.store')" resources/views/checkout/simple-index.blade.php; then
    echo -e "${GREEN}✅ Action do formulário configurada corretamente${NC}"
else
    echo -e "${RED}❌ ERRO: Action do formulário não encontrada${NC}"
    ERRORS=$((ERRORS + 1))
fi

if grep -q '@csrf' resources/views/checkout/simple-index.blade.php; then
    echo -e "${GREEN}✅ Token CSRF presente no formulário${NC}"
else
    echo -e "${RED}❌ ERRO: Token CSRF não encontrado${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "5. Verificando rotas de checkout..."
if grep -q 'checkout.store' routes/web.php; then
    echo -e "${GREEN}✅ Rota checkout.store configurada${NC}"
else
    echo -e "${RED}❌ ERRO: Rota checkout.store não encontrada${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "6. Verificando OrderController..."
if [ -f "app/Http/Controllers/OrderController.php" ]; then
    echo -e "${GREEN}✅ OrderController existe${NC}"
    
    if grep -q "pickup_time" app/Http/Controllers/OrderController.php; then
        echo -e "${GREEN}✅ OrderController processa campo pickup_time${NC}"
    else
        echo -e "${YELLOW}⚠️  AVISO: Campo pickup_time não encontrado no OrderController${NC}"
    fi
    
    if grep -q "payment_method" app/Http/Controllers/OrderController.php; then
        echo -e "${GREEN}✅ OrderController processa campo payment_method${NC}"
    else
        echo -e "${YELLOW}⚠️  AVISO: Campo payment_method não encontrado no OrderController${NC}"
    fi
else
    echo -e "${RED}❌ ERRO: OrderController não encontrado${NC}"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "======================================"
echo "RESULTADO DA VALIDAÇÃO"
echo "======================================"

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ TODAS AS CORREÇÕES FORAM APLICADAS COM SUCESSO!${NC}"
    echo ""
    echo "O sistema está pronto para processar pedidos."
    echo ""
    echo "Próximos passos:"
    echo "1. Iniciar o servidor: php artisan serve"
    echo "2. Testar o checkout manualmente"
    echo "3. Monitorar os logs em storage/logs/laravel.log"
    exit 0
else
    echo -e "${RED}❌ FORAM ENCONTRADOS $ERRORS ERRO(S)${NC}"
    echo ""
    echo "Por favor, revise as correções acima."
    exit 1
fi
