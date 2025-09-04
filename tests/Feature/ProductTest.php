<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar categorias de teste
        Category::factory()->create(['name' => 'Frutas', 'slug' => 'frutas']);
        Category::factory()->create(['name' => 'Verduras', 'slug' => 'verduras']);
        Category::factory()->create(['name' => 'Legumes', 'slug' => 'legumes']);
    }

    public function test_home_page_displays_featured_products()
    {
        // Criar usuário e produtos
        $user = User::factory()->create();
        $category = Category::first();
        
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->price);
    }

    public function test_products_index_page_displays_all_products()
    {
        $user = User::factory()->create();
        $category = Category::first();
        
        $products = Product::factory(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_product_show_page_displays_product_details()
    {
        $user = User::factory()->create();
        $category = Category::first();
        
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee($product->price);
        $response->assertSee($product->user->name);
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $category = Category::first();

        $productData = [
            'name' => 'Tomate Orgânico',
            'description' => 'Tomate fresco e orgânico',
            'price' => 8.50,
            'stock_quantity' => 50,
            'unit' => 'kg',
            'category_id' => $category->id,
            'origin' => 'Fazenda Teste'
        ];

        $response = $this->actingAs($user)->post('/products', $productData);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Tomate Orgânico',
            'user_id' => $user->id
        ]);
    }

    public function test_guest_cannot_create_product()
    {
        $category = Category::first();

        $productData = [
            'name' => 'Tomate Orgânico',
            'description' => 'Tomate fresco e orgânico',
            'price' => 8.50,
            'stock_quantity' => 50,
            'unit' => 'kg',
            'category_id' => $category->id
        ];

        $response = $this->post('/products', $productData);

        $response->assertRedirect('/login');
    }

    public function test_user_can_only_edit_own_products()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $category = Category::first();
        
        $product = Product::factory()->create([
            'user_id' => $user1->id,
            'category_id' => $category->id
        ]);

        // User2 tentando editar produto do User1
        $response = $this->actingAs($user2)->get("/products/{$product->id}/edit");
        $response->assertStatus(403);

        // User1 editando seu próprio produto
        $response = $this->actingAs($user1)->get("/products/{$product->id}/edit");
        $response->assertStatus(200);
    }

    public function test_product_search_functionality()
    {
        $user = User::factory()->create();
        $category = Category::first();
        
        $product1 = Product::factory()->create([
            'name' => 'Tomate Orgânico',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);
        
        $product2 = Product::factory()->create([
            'name' => 'Banana Prata',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->get('/products?search=tomate');

        $response->assertStatus(200);
        $response->assertSee($product1->name);
        $response->assertDontSee($product2->name);
    }

    public function test_product_category_filter()
    {
        $user = User::factory()->create();
        $frutas = Category::where('name', 'Frutas')->first();
        $verduras = Category::where('name', 'Verduras')->first();
        
        $fruta = Product::factory()->create([
            'name' => 'Banana',
            'user_id' => $user->id,
            'category_id' => $frutas->id,
            'active' => true
        ]);
        
        $verdura = Product::factory()->create([
            'name' => 'Tomate',
            'user_id' => $user->id,
            'category_id' => $verduras->id,
            'active' => true
        ]);

        $response = $this->get("/products?category={$frutas->id}");

        $response->assertStatus(200);
        $response->assertSee($fruta->name);
        $response->assertDontSee($verdura->name);
    }

    public function test_inactive_products_are_not_displayed()
    {
        $user = User::factory()->create();
        $category = Category::first();
        
        $activeProduct = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => true
        ]);
        
        $inactiveProduct = Product::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'active' => false
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee($activeProduct->name);
        $response->assertDontSee($inactiveProduct->name);
    }

    public function test_product_validation_rules()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/products', [
            'name' => '', // Nome obrigatório
            'price' => -10, // Preço deve ser positivo
            'stock_quantity' => -5, // Estoque deve ser positivo
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'stock_quantity', 'category_id', 'description', 'unit']);
    }
}
