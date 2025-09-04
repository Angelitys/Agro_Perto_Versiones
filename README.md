# AgroPerto - Marketplace da Agricultura Familiar

![AgroPerto Logo](https://via.placeholder.com/200x100/4ade80/ffffff?text=AgroPerto)

**Versão:** 1.0.0  
**Data:** Agosto 2025  
**Desenvolvido por:** Manus AI  
**Tecnologia:** Laravel 11 + MariaDB + Tailwind CSS

## Visão Geral

O AgroPerto é uma plataforma web completa desenvolvida para conectar produtores rurais diretamente aos consumidores, promovendo a agricultura familiar sustentável no Brasil. O sistema oferece um marketplace moderno e intuitivo onde pequenos produtores podem cadastrar e vender seus produtos frescos, enquanto consumidores têm acesso direto a alimentos de qualidade com origem conhecida.

### Missão

Fortalecer a economia rural brasileira através da tecnologia, eliminando intermediários desnecessários e garantindo preços justos tanto para produtores quanto para consumidores, promovendo assim a sustentabilidade da agricultura familiar.

### Características Principais

- **Marketplace Completo**: Sistema de cadastro, busca e compra de produtos
- **Gestão de Produtos**: CRUD completo com upload de imagens e categorização
- **Carrinho de Compras**: Sistema intuitivo de seleção e compra
- **Sistema de Pedidos**: Acompanhamento completo do processo de venda
- **Autenticação Segura**: Sistema robusto de login e registro
- **Interface Responsiva**: Design adaptável para desktop e mobile
- **Painel Administrativo**: Gestão completa para produtores

## Índice

1. [Instalação e Configuração](#instalação-e-configuração)
2. [Arquitetura do Sistema](#arquitetura-do-sistema)
3. [Funcionalidades](#funcionalidades)
4. [Guia do Desenvolvedor](#guia-do-desenvolvedor)
5. [API Documentation](#api-documentation)
6. [Testes](#testes)
7. [Deploy](#deploy)
8. [Contribuição](#contribuição)
9. [Licença](#licença)

---

## Instalação e Configuração

### Pré-requisitos

- **PHP 8.2+** com extensões: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer 2.0+**
- **Node.js 18+** e npm
- **MariaDB 10.6+** ou MySQL 8.0+
- **Servidor web** (Apache/Nginx) ou PHP built-in server para desenvolvimento

### Instalação Passo a Passo

#### 1. Clone o Repositório

```bash
git clone https://github.com/seu-usuario/agro-marketplace-laravel.git
cd agro-marketplace-laravel
```

#### 2. Instale as Dependências PHP

```bash
composer install
```

#### 3. Instale as Dependências Node.js

```bash
npm install
```

#### 4. Configure o Ambiente

```bash
# Copie o arquivo de configuração
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate
```

#### 5. Configure o Banco de Dados

Edite o arquivo `.env` com suas credenciais do MariaDB:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agro_marketplace_laravel
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

#### 6. Execute as Migrações

```bash
# Criar o banco de dados
mysql -u root -p -e "CREATE DATABASE agro_marketplace_laravel"

# Executar migrações
php artisan migrate

# Popular com dados iniciais
php artisan db:seed
```

#### 7. Configure o Storage

```bash
php artisan storage:link
```

#### 8. Compile os Assets

```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

#### 9. Inicie o Servidor

```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

### Configurações Adicionais

#### Upload de Arquivos

Configure os limites de upload no arquivo `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

#### Configuração de Email

Para funcionalidades de email, configure no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp-host
MAIL_PORT=587
MAIL_USERNAME=seu-email
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
```

---

## Arquitetura do Sistema

### Visão Geral da Arquitetura

O AgroPerto segue o padrão MVC (Model-View-Controller) do Laravel, implementando uma arquitetura limpa e escalável:

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │    Backend      │    │   Database      │
│   (Blade/JS)    │◄──►│   (Laravel)     │◄──►│   (MariaDB)     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Estrutura de Diretórios

```
agro-marketplace-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores da aplicação
│   │   ├── Middleware/      # Middlewares personalizados
│   │   └── Requests/        # Form Requests para validação
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Serviços de negócio
├── database/
│   ├── factories/           # Factories para testes
│   ├── migrations/          # Migrações do banco
│   └── seeders/             # Seeders para dados iniciais
├── resources/
│   ├── views/               # Templates Blade
│   ├── js/                  # JavaScript/Alpine.js
│   └── css/                 # Estilos CSS/Tailwind
├── routes/
│   ├── web.php              # Rotas web
│   └── api.php              # Rotas da API
├── tests/
│   ├── Feature/             # Testes de funcionalidade
│   └── Unit/                # Testes unitários
└── public/                  # Assets públicos
```

### Modelos de Dados

#### Diagrama ER Simplificado

```
Users ──┐
        ├── Products
        ├── Carts ──── CartItems ──── Products
        ├── Orders ──── OrderItems ──── Products
        └── Addresses

Categories ──── Products
```

#### Principais Modelos

- **User**: Usuários do sistema (produtores e consumidores)
- **Category**: Categorias de produtos (Frutas, Verduras, etc.)
- **Product**: Produtos cadastrados pelos produtores
- **Cart**: Carrinho de compras do usuário
- **CartItem**: Itens individuais no carrinho
- **Order**: Pedidos realizados
- **OrderItem**: Itens individuais do pedido
- **Address**: Endereços de entrega dos usuários

---

## Funcionalidades

### Para Consumidores

#### Navegação e Descoberta de Produtos

O AgroPerto oferece uma experiência de navegação intuitiva e eficiente para consumidores que buscam produtos frescos da agricultura familiar. A página inicial apresenta um design limpo e moderno, destacando produtos em destaque e facilitando a descoberta de novos itens através de categorias bem organizadas.

Os usuários podem navegar pelos produtos utilizando múltiplas abordagens. O sistema de categorização permite filtrar produtos por tipo (Frutas, Verduras, Legumes, Grãos e Cereais, Laticínios, entre outros), facilitando a localização de itens específicos. Além disso, a funcionalidade de busca avançada permite pesquisar produtos por nome, descrição ou origem, utilizando algoritmos de busca otimizados que garantem resultados relevantes mesmo com termos parciais.

Cada produto é apresentado com informações detalhadas incluindo preço por unidade (kg, maço, dúzia), quantidade disponível em estoque, origem do produto com localização do produtor, data de colheita quando aplicável, e imagens de alta qualidade que mostram a aparência real do produto. Esta transparência é fundamental para construir confiança entre produtores e consumidores.

#### Sistema de Carrinho de Compras

O carrinho de compras foi desenvolvido com foco na usabilidade e eficiência. Os usuários podem adicionar produtos diretamente das páginas de listagem ou detalhes, especificando a quantidade desejada. O sistema valida automaticamente a disponibilidade em estoque, impedindo a adição de quantidades superiores ao disponível.

O carrinho mantém o estado entre sessões, permitindo que usuários adicionem produtos em diferentes momentos e finalizem a compra quando conveniente. A interface do carrinho exibe claramente cada item com sua respectiva quantidade, preço unitário, subtotal e total geral da compra. Usuários podem facilmente modificar quantidades, remover itens específicos ou limpar completamente o carrinho.

O cálculo de totais é realizado em tempo real, considerando possíveis promoções ou descontos aplicáveis. O sistema também fornece estimativas de peso total da compra, informação útil para cálculo de frete quando implementado.

#### Processo de Checkout e Pedidos

O processo de finalização de compra foi simplificado para reduzir o abandono de carrinho. Usuários autenticados podem proceder diretamente ao checkout, onde podem selecionar ou cadastrar endereços de entrega. O sistema suporta múltiplos endereços por usuário, facilitando entregas para diferentes locais.

Após a confirmação do pedido, o sistema gera automaticamente um número de acompanhamento único. Os usuários recebem confirmação por email (quando configurado) e podem acompanhar o status do pedido através do painel pessoal. Os status incluem: Pendente, Confirmado, Em Preparação, Enviado e Entregue.

### Para Produtores

#### Gestão Completa de Produtos

Produtores têm acesso a um painel administrativo completo para gerenciar seus produtos. O sistema permite cadastrar novos produtos com informações detalhadas incluindo nome, descrição completa, categoria, preço, unidade de venda, quantidade em estoque, origem/localização da propriedade, e data de colheita.

O upload de imagens é suportado com redimensionamento automático e otimização para web, garantindo carregamento rápido das páginas. Produtores podem adicionar múltiplas imagens por produto, sendo a primeira definida como imagem principal.

A edição de produtos é intuitiva, permitindo atualizações rápidas de preços, estoque ou informações. O sistema mantém histórico de alterações para auditoria. Produtos podem ser marcados como ativos ou inativos, controlando sua visibilidade no marketplace sem perder os dados.

#### Gerenciamento de Pedidos

Produtores recebem notificações de novos pedidos e podem gerenciar o status através do painel administrativo. O sistema exibe todos os pedidos organizados por status, permitindo filtros por data, valor ou cliente.

Para cada pedido, o produtor pode visualizar informações completas do cliente, endereço de entrega, itens solicitados com quantidades, e valor total. O sistema facilita a atualização de status, permitindo comunicação transparente com os clientes sobre o andamento dos pedidos.

#### Relatórios e Analytics

O painel do produtor inclui relatórios básicos sobre vendas, produtos mais vendidos, e performance geral. Estas informações ajudam produtores a tomar decisões informadas sobre produção e precificação.

### Funcionalidades Técnicas

#### Sistema de Autenticação

O AgroPerto utiliza o Laravel Breeze para autenticação, fornecendo um sistema robusto e seguro. As funcionalidades incluem registro de novos usuários com validação de email, login seguro com proteção contra ataques de força bruta, recuperação de senha via email, e sessões seguras com tokens CSRF.

O sistema suporta diferentes tipos de usuário (consumidor e produtor) com permissões específicas. Produtores têm acesso adicional às funcionalidades de gestão de produtos e pedidos, enquanto consumidores focam na navegação e compra.

#### Segurança

A aplicação implementa múltiplas camadas de segurança incluindo validação de entrada em todos os formulários, proteção contra injeção SQL através do ORM Eloquent, sanitização de dados de saída, proteção CSRF em todas as operações de modificação, e hash seguro de senhas usando bcrypt.

O sistema também implementa rate limiting para prevenir abuso de APIs, validação de tipos de arquivo para uploads, e sanitização de nomes de arquivo para prevenir vulnerabilidades.

#### Performance e Escalabilidade

O AgroPerto foi desenvolvido com performance em mente. O sistema utiliza cache de consultas frequentes, otimização de imagens automática, paginação eficiente para listagens grandes, e índices de banco de dados otimizados.

A arquitetura permite escalabilidade horizontal através de load balancers e múltiplos servidores de aplicação. O banco de dados pode ser facilmente replicado para distribuir a carga de leitura.

---

## Guia do Desenvolvedor

### Convenções de Código

O projeto segue as convenções padrão do Laravel e PSR-12 para PHP. Algumas convenções específicas incluem:

- **Nomenclatura**: Classes em PascalCase, métodos e variáveis em camelCase, constantes em UPPER_CASE
- **Estrutura**: Controllers no singular, Models no singular, Migrations descritivas
- **Comentários**: PHPDoc para todos os métodos públicos, comentários inline para lógica complexa
- **Validação**: Form Requests para validação complexa, validação inline para casos simples

### Estrutura do Banco de Dados

#### Tabela Users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    type ENUM('consumer', 'producer') DEFAULT 'consumer',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### Tabela Categories
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### Tabela Products
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    unit VARCHAR(50) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    origin VARCHAR(255) NULL,
    harvest_date DATE NULL,
    images JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
```

### Principais Controllers

#### HomeController
Responsável pela página inicial, busca de produtos e funcionalidades gerais do marketplace.

```php
class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['user', 'category'])
            ->where('active', true)
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->limit(6)
            ->get();

        $categories = Category::where('active', true)
            ->withCount(['products' => function ($query) {
                $query->where('active', true);
            }])
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
```

#### ProductController
Gerencia todas as operações relacionadas a produtos incluindo CRUD completo.

```php
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category'])
            ->where('active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::where('active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }
}
```

### Modelos Eloquent

#### Product Model
```php
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'stock_quantity',
        'unit', 'user_id', 'category_id', 'active', 'origin',
        'harvest_date', 'images'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
        'harvest_date' => 'date',
        'images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getMainImageAttribute()
    {
        return $this->images ? $this->images[0] : '/images/no-image.png';
    }
}
```

### Rotas Principais

```php
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store']);
});
```

### Middleware Personalizado

O sistema utiliza middlewares para controle de acesso e validação:

```php
// app/Http/Middleware/ProducerOnly.php
class ProducerOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->type !== 'producer') {
            abort(403, 'Acesso negado. Apenas produtores podem acessar esta área.');
        }

        return $next($request);
    }
}
```

---

