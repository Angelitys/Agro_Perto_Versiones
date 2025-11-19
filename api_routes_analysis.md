# Análise das Rotas da API AgroPerto

Este documento detalha as rotas da API identificadas no projeto AgroPerto, comparando a saída do comando `php artisan route:list --json` com as definições em `routes/api.php` e os requisitos do utilizador.

## Rotas da API Extraídas (`api_routes.json`)

Foram identificadas 27 rotas de API. Abaixo está a lista das rotas extraídas:

```json
[
    {
        "domain": null,
        "method": "POST",
        "uri": "api/cart/add",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\CartController@addToCart",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/cart/clear",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\CartController@clearCart",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/cart/remove",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\CartController@removeFromCart",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/cart/{user_id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\CartController@getCart",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/categories",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\CategoryController@categories",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/login",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@login",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/notifications",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\NotificationController@index",
        "middleware": [
            "api",
            "App\\Http\\Middleware\\Authenticate:sanctum"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/notifications/mark-all-as-read",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\NotificationController@markAllAsRead",
        "middleware": [
            "api",
            "App\\Http\\Middleware\\Authenticate:sanctum"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/notifications/unread-count",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\NotificationController@getUnreadCount",
        "middleware": [
            "api",
            "App\\Http\\Middleware\\Authenticate:sanctum"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/notifications/{id}/mark-as-read",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\NotificationController@markAsRead",
        "middleware": [
            "api",
            "App\\Http\\Middleware\\Authenticate:sanctum"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/orders",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\OrderController@index",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/orders",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\OrderController@store",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/orders/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\OrderController@show",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "PUT",
        "uri": "api/orders/{id}/pickup-schedule",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\OrderController@updatePickupSchedule",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/orders/{id}/confirm-delivery",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\OrderController@confirmDelivery",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/products",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@products",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/products",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@addProduct",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/products/category/{category_id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@productsByCategory",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/products/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@product",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "PUT",
        "uri": "api/products/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@updateProduct",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "DELETE",
        "uri": "api/products/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\ProductController@deleteProduct",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "POST",
        "uri": "api/register",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@register",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/user",
        "name": null,
        "action": "Closure",
        "middleware": [
            "api",
            "App\\Http\\Middleware\\Authenticate:sanctum"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/users",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@users",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "GET|HEAD",
        "uri": "api/users/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@getUser",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "PUT",
        "uri": "api/users/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@updateUser",
        "middleware": [
            "api"
        ]
    },
    {
        "domain": null,
        "method": "DELETE",
        "uri": "api/users/{id}",
        "name": null,
        "action": "App\\Http\\Controllers\\Api\\UserController@deleteUser",
        "middleware": [
            "api"
        ]
    }
]
```

## Comparação com `routes/api.php`

Todas as rotas API definidas no ficheiro `routes/api.php` estão agora corretamente listadas na saída JSON, incluindo as rotas adicionadas para gestão de produtos (criar, atualizar, eliminar e pesquisa por categoria), gestão de utilizadores (obter, atualizar, eliminar) e gestão de pedidos (obter, criar, visualizar detalhe, atualizar agendamento de recolha e confirmar entrega).

## Análise de Funcionalidades Principais e Rotas em Falta (Atualizado)

Com base nos requisitos do utilizador e nas funcionalidades principais esperadas, foram identificadas as seguintes observações e potenciais rotas em falta:

### Funcionalidades Presentes (com rotas API):

*   **Gestão de Carrinho**: `api/cart/add`, `api/cart/remove`, `api/cart/clear`, `api/cart/{user_id}`
*   **Categorias**: `api/categories`
*   **Autenticação**: `api/login`, `api/register`
*   **Notificações**: `api/notifications`, `api/notifications/{id}/mark-as-read`, `api/notifications/mark-all-as-read`, `api/notifications/unread-count`
*   **Pedidos**: `api/orders` (GET e POST), `api/orders/{id}` (GET), `api/orders/{id}/pickup-schedule` (PUT), `api/orders/{id}/confirm-delivery` (POST)
*   **Produtos**: `api/products` (GET, POST, PUT, DELETE), `api/products/{id}` (GET, PUT, DELETE), `api/products/category/{category_id}`
*   **Informações do Utilizador**: `api/user`
*   **Gestão de Utilizadores (CRUD)**: `api/users` (GET), `api/users/{id}` (GET, PUT, DELETE)

### Funcionalidades com Rotas API Potencialmente em Falta ou Incompletas:

1.  **Registo de Produtores/Vendedores**: Embora exista `api/register` para utilizadores gerais e agora a gestão de utilizadores (CRUD) esteja implementada, ainda não há uma rota API explícita para o registo de produtores/vendedores com campos específicos ou um fluxo de aprovação, conforme sugerido pelos requisitos (`Vendor registration`). A funcionalidade `api/register` pode ser genérica e o tipo de utilizador (`consumer` ou `producer`) é definido no registo, mas um fluxo mais robusto para produtores pode ser necessário.
2.  **Sistema de Feedback do Utilizador/Avaliação de Clientes**: Não foram encontradas rotas API para submeter feedback ou avaliações de clientes (`User feedback system`, `Customer evaluation system`). As rotas `reviews.product` e `reviews.available` listadas no `routes.json` geral são rotas `web`, não API.
3.  **Dashboard de Vendas com Relatórios**: Não foram identificadas rotas API para aceder a dados de vendas ou relatórios para o dashboard (`Sales dashboard with reports`). As rotas `sales.index` e `sales.show` listadas no `routes.json` geral são rotas `web`, não API.
4.  **Notificações WhatsApp**: Embora exista um sistema de notificações API, não há indicação explícita de rotas ou endpoints para a integração ou envio de notificações via WhatsApp (`WhatsApp notifications`).

## Próximos Passos

Os próximos passos incluirão a implementação das funcionalidades restantes e a criação das rotas API correspondentes, bem como a validação de todas as funcionalidades existentes através de testes. Será dada prioridade ao registo de produtores/vendedores, sistema de feedback e relatórios de vendas, e integração de notificações WhatsApp.
