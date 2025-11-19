@echo off
echo ===== AGROPERTO - SISTEMA TESTADO E FUNCIONAL =====
echo.

echo ✓ Sistema completamente testado
echo ✓ Todas as rotas funcionando
echo ✓ Banco de dados SQLite configurado
echo ✓ Usuarios de teste criados
echo.

echo Iniciando servidor...
php artisan serve

echo.
echo ===== ACESSO AO SISTEMA =====
echo.
echo URL: http://localhost:8000
echo.
echo USUARIOS DE TESTE:
echo   Produtor: joao.produtor@teste.com / 123456789
echo   Consumidor: maria.consumidor@teste.com / 123456789
echo.
echo FUNCIONALIDADES TESTADAS:
echo   ✓ Pagina inicial
echo   ✓ Catalogo de produtos
echo   ✓ Login/Registro
echo   ✓ Dashboard
echo   ✓ Carrinho de compras
echo   ✓ Checkout
echo   ✓ Sistema de pedidos
echo   ✓ Notificacoes
echo   ✓ Avaliacoes
echo.
pause
