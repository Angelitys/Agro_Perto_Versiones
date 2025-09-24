# API Documentation - AgroPerto

## Visão Geral

A API do AgroPerto fornece endpoints RESTful para integração com aplicações externas, permitindo acesso programático às funcionalidades principais do marketplace. A API segue os padrões REST e utiliza autenticação via tokens para garantir segurança.

**Base URL:** `https://seu-dominio.com/api`  
**Versão:** v1  
**Formato:** JSON  
**Autenticação:** Bearer Token (Laravel Sanctum)

## Autenticação

### Obter Token de Acesso

**Endpoint:** `POST /api/auth/login`

**Request:**
```json
{
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "João Silva",
            "email": "joao@fazenda.com",
            "type": "producer"
        },
        "token": "1|abcdef123456789..."
    },
    "message": "Login realizado com sucesso"
}
```

**Response (401):**
```json
{
    "success": false,
    "message": "Credenciais inválidas"
}
```

### Registro de Usuário

**Endpoint:** `POST /api/auth/register`

**Request:**
```json
{
    "name": "Maria Santos",
    "email": "maria@exemplo.com",
    "password": "senha123",
    "password_confirmation": "senha123",
    "type": "consumer"
}
```

**Response (201):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 2,
            "name": "Maria Santos",
            "email": "maria@exemplo.com",
            "type": "consumer"
        },
        "token": "2|xyz789456123..."
    },
    "message": "Usuário registrado com sucesso"
}
```

### Logout

**Endpoint:** `POST /api/auth/logout`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Logout realizado com sucesso"
}
```

## Produtos

### Listar Produtos

**Endpoint:** `GET /api/products`

**Parâmetros de Query:**
- `page` (int): Número da página (padrão: 1)
- `per_page` (int): Itens por página (padrão: 15, máximo: 50)
- `search` (string): Termo de busca
- `category_id` (int): ID da categoria
- `user_id` (int): ID do produtor
- `active` (boolean): Filtrar por produtos ativos

**Response (200):**
```json
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 1,
                "name": "Tomates Orgânicos",
                "slug": "tomates-organicos-123",
                "description": "Tomates frescos cultivados sem agrotóxicos...",
                "price": "8.50",
                "formatted_price": "R$ 8,50",
                "stock_quantity": 50,
                "unit": "kg",
                "active": true,
                "origin": "Fazenda Boa Vista, MG",
                "harvest_date": "2025-08-25",
                "main_image": "/storage/products/tomate1.jpg",
                "images": ["/storage/products/tomate1.jpg", "/storage/products/tomate2.jpg"],
                "created_at": "2025-08-29T10:00:00.000000Z",
                "updated_at": "2025-08-29T10:00:00.000000Z",
                "user": {
                    "id": 1,
                    "name": "João Silva",
                    "email": "joao@fazenda.com"
                },
                "category": {
                    "id": 2,
                    "name": "Verduras",
                    "slug": "verduras"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 45,
            "last_page": 3,
            "from": 1,
            "to": 15
        }
    }
}
```

### Obter Produto Específico

**Endpoint:** `GET /api/products/{id}`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Tomates Orgânicos",
        "slug": "tomates-organicos-123",
        "description": "Tomates frescos cultivados sem agrotóxicos na Fazenda Boa Vista...",
        "price": "8.50",
        "formatted_price": "R$ 8,50",
        "stock_quantity": 50,
        "unit": "kg",
        "active": true,
        "origin": "Fazenda Boa Vista, MG",
        "harvest_date": "2025-08-25",
        "images": ["/storage/products/tomate1.jpg", "/storage/products/tomate2.jpg"],
        "created_at": "2025-08-29T10:00:00.000000Z",
        "updated_at": "2025-08-29T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "João Silva",
            "email": "joao@fazenda.com",
            "phone": "(31) 99999-9999"
        },
        "category": {
            "id": 2,
            "name": "Verduras",
            "slug": "verduras",
            "description": "Verduras frescas e orgânicas"
        }
    }
}
```

**Response (404):**
```json
{
    "success": false,
    "message": "Produto não encontrado"
}
```

### Criar Produto (Apenas Produtores)

**Endpoint:** `POST /api/products`  
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "name": "Cenouras Baby",
    "description": "Cenouras baby tenras e doces, perfeitas para lanches...",
    "price": 6.80,
    "stock_quantity": 25,
    "unit": "kg",
    "category_id": 3,
    "origin": "Horta Familiar, RJ",
    "harvest_date": "2025-08-28"
}
```

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 15,
        "name": "Cenouras Baby",
        "slug": "cenouras-baby-456",
        "description": "Cenouras baby tenras e doces...",
        "price": "6.80",
        "stock_quantity": 25,
        "unit": "kg",
        "user_id": 1,
        "category_id": 3,
        "active": true,
        "origin": "Horta Familiar, RJ",
        "harvest_date": "2025-08-28",
        "created_at": "2025-08-29T14:30:00.000000Z"
    },
    "message": "Produto criado com sucesso"
}
```

### Atualizar Produto

**Endpoint:** `PUT /api/products/{id}`  
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "name": "Cenouras Baby Premium",
    "price": 7.20,
    "stock_quantity": 30
}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 15,
        "name": "Cenouras Baby Premium",
        "price": "7.20",
        "stock_quantity": 30,
        "updated_at": "2025-08-29T15:00:00.000000Z"
    },
    "message": "Produto atualizado com sucesso"
}
```

### Excluir Produto

**Endpoint:** `DELETE /api/products/{id}`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Produto excluído com sucesso"
}
```

## Categorias

### Listar Categorias

**Endpoint:** `GET /api/categories`

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Frutas",
            "slug": "frutas",
            "description": "Frutas frescas e saborosas",
            "active": true,
            "products_count": 12
        },
        {
            "id": 2,
            "name": "Verduras",
            "slug": "verduras",
            "description": "Verduras frescas e orgânicas",
            "active": true,
            "products_count": 18
        }
    ]
}
```

## Carrinho de Compras

### Obter Carrinho do Usuário

**Endpoint:** `GET /api/cart`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 2,
        "total": "24.30",
        "formatted_total": "R$ 24,30",
        "items_count": 3,
        "total_quantity": 5,
        "items": [
            {
                "id": 1,
                "cart_id": 1,
                "product_id": 1,
                "quantity": 2,
                "price": "8.50",
                "subtotal": "17.00",
                "formatted_subtotal": "R$ 17,00",
                "product": {
                    "id": 1,
                    "name": "Tomates Orgânicos",
                    "unit": "kg",
                    "main_image": "/storage/products/tomate1.jpg"
                }
            },
            {
                "id": 2,
                "cart_id": 1,
                "product_id": 3,
                "quantity": 1,
                "price": "4.20",
                "subtotal": "4.20",
                "formatted_subtotal": "R$ 4,20",
                "product": {
                    "id": 3,
                    "name": "Bananas Prata",
                    "unit": "kg",
                    "main_image": "/storage/products/banana1.jpg"
                }
            }
        ]
    }
}
```

### Adicionar Item ao Carrinho

**Endpoint:** `POST /api/cart/items`  
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "product_id": 1,
    "quantity": 2
}
```

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 3,
        "cart_id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": "8.50",
        "subtotal": "17.00"
    },
    "message": "Item adicionado ao carrinho"
}
```

### Atualizar Quantidade do Item

**Endpoint:** `PUT /api/cart/items/{id}`  
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "quantity": 3
}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 3,
        "quantity": 3,
        "subtotal": "25.50",
        "formatted_subtotal": "R$ 25,50"
    },
    "message": "Quantidade atualizada"
}
```

### Remover Item do Carrinho

**Endpoint:** `DELETE /api/cart/items/{id}`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Item removido do carrinho"
}
```

### Limpar Carrinho

**Endpoint:** `DELETE /api/cart`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Carrinho limpo com sucesso"
}
```

## Pedidos

### Listar Pedidos do Usuário

**Endpoint:** `GET /api/orders`  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "order_number": "AGR-2025-001",
            "status": "confirmed",
            "status_label": "Confirmado",
            "total": "45.60",
            "formatted_total": "R$ 45,60",
            "items_count": 3,
            "created_at": "2025-08-29T09:00:00.000000Z",
            "items": [
                {
                    "id": 1,
                    "product_id": 1,
                    "quantity": 2,
                    "price": "8.50",
                    "subtotal": "17.00",
                    "product": {
                        "name": "Tomates Orgânicos",
                        "unit": "kg"
                    }
                }
            ]
        }
    ]
}
```

### Criar Pedido

**Endpoint:** `POST /api/orders`  
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "address_id": 1,
    "notes": "Entregar pela manhã, se possível"
}
```

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "order_number": "AGR-2025-002",
        "status": "pending",
        "total": "24.30",
        "created_at": "2025-08-29T16:00:00.000000Z"
    },
    "message": "Pedido criado com sucesso"
}
```

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **201 Created**: Recurso criado com sucesso
- **400 Bad Request**: Dados de entrada inválidos
- **401 Unauthorized**: Token de autenticação inválido ou ausente
- **403 Forbidden**: Usuário não tem permissão para acessar o recurso
- **404 Not Found**: Recurso não encontrado
- **422 Unprocessable Entity**: Erro de validação
- **500 Internal Server Error**: Erro interno do servidor

## Tratamento de Erros

Todas as respostas de erro seguem o formato padrão:

```json
{
    "success": false,
    "message": "Mensagem de erro",
    "errors": {
        "campo": ["Erro específico do campo"]
    }
}
```

### Exemplo de Erro de Validação (422):

```json
{
    "success": false,
    "message": "Os dados fornecidos são inválidos",
    "errors": {
        "name": ["O campo nome é obrigatório"],
        "price": ["O preço deve ser maior que zero"]
    }
}
```

## Rate Limiting

A API implementa rate limiting para prevenir abuso:

- **Autenticação**: 5 tentativas por minuto por IP
- **Endpoints gerais**: 60 requisições por minuto por usuário autenticado
- **Endpoints públicos**: 100 requisições por minuto por IP

Quando o limite é excedido, a API retorna status 429 (Too Many Requests).

## Versionamento

A API utiliza versionamento via URL. A versão atual é v1 e está incluída na base URL: `/api/v1/`

Futuras versões manterão compatibilidade com versões anteriores sempre que possível.

