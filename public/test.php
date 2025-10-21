<?php

// Teste simples para verificar se o sistema está funcionando
header('Content-Type: application/json');

echo json_encode([
    'status' => 'OK',
    'message' => 'AgroPerto Marketplace - Sistema funcionando!',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'php_version' => PHP_VERSION,
    'features_implemented' => [
        'venda_produtos' => 'Venda de produtos para produtores que estão na feira',
        'cadastrar_produtos' => 'Cadastrar produtos',
        'cadastrar_clientes' => 'Cadastrar Clientes',
        'cadastrar_vendedores' => 'Cadastrar Vendedores',
        'realizar_pedidos' => 'Realizar Pedidos',
        'lista_produtos_categoria' => 'Lista de itens com pesquisa por categoria',
        'agenda_retirada' => 'Agenda de retirada dos produtos conforme a data de disponibilidade',
        'confirmacao_entrega' => 'Confirmação de entrega',
        'feedback_usuarios' => 'Feedback dos usuários com o produto e o produtor',
        'dashboard_vendas' => 'Dashboard de vendas para o produtor, com relatórios',
        'avaliacao_clientes' => 'Avaliação de clientes que compram e não vão buscar'
    ],
    'pending_features' => [
        'notificacoes_email' => 'Envio de notificações por email',
        'notificacoes_whatsapp' => 'Notificações por WhatsApp',
        'termos_uso' => 'Termos de uso / legalidade'
    ]
], JSON_PRETTY_PRINT);
?>
