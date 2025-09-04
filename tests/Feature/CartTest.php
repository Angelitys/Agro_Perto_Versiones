<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar categorias de teste
        Category::factory()->create(['name' => 'Frutas', 'slug' => 'frutas']);
        Category::factory()->create(['name' => 'Verduras', 'slug' => 'verduras']);
    }

    public function test_authenticated_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $category = Category::first();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'active' => true,
            'stock_quantity' => 10
        ]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }

    public function test_guest_cannot_add_product_to_cart()
    {
        $category = Category::first();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_cart()
    {
        $user = User::factory()->create();
        $category = Category::first();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'active' => true
        ]);

        // Criar carrinho e item
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price
        ]);

        $response = $this->actingAs($user)->get('/cart');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('2'); // quantidade
    }

    public function test_cart_calculates_total_correctly()
    {
        $user = User::factory()->create();
        $category = Category::first();
        
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 10.00,
            'active' => true
        ]);
        
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 5.50,
            'active' => true
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => $product1->price
        ]);
        
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 3,
            'price' => $product2->price
        ]);

        $cart->refresh();
        
        // Total: (10.00 * 2) + (5.50 * 3) = 20.00 + 16.50 = 36.50
        $this->assertEquals(36.50, $cart->total);
    }

    public function test_cannot_add_inactive_product_to_cart()
    {
        $user = User::factory()->create();
        $category = Category::first();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'active' => false
        ]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertStatus(404);
    }
}
