<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Exibir o carrinho
     */
    public function index()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load(['items.product']);
        
        return view('cart.index', compact('cart'));
    }

    /**
     * Adicionar produto ao carrinho
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity
        ]);

        $cart = auth()->user()->getOrCreateCart();
        
        \Log::info("CartController@add: Adding product to cart", ["product_id" => $product->id, "quantity" => $request->quantity, "user_id" => auth()->id()]);

        // Verificar se o produto já está no carrinho
        $cartItem = $cart->items()->where("product_id", $product->id)->first();
        
        if ($cartItem) {
            // Atualizar quantidade
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Quantidade solicitada excede o estoque disponível.');
            }
            
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price // Atualizar preço se mudou
            ]);
        } else {
            // Criar novo item no carrinho
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    /**
     * Atualizar quantidade do item no carrinho
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Verificar se o item pertence ao usuário atual
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock_quantity
        ]);

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Carrinho atualizado!');
    }

    /**
     * Remover item do carrinho
     */
    public function remove(CartItem $cartItem)
    {
        // Verificar se o item pertence ao usuário atual
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removido do carrinho!');
    }

    /**
     * Limpar carrinho
     */
    public function clear()
    {
        $cart = auth()->user()->cart;
        
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Carrinho limpo!');
    }

    /**
     * Obter contagem de itens no carrinho (AJAX)
     */
    public function count()
    {
        $cart = auth()->user()->cart;
        $count = $cart ? $cart->total_items : 0;
        
        return response()->json(['count' => $count]);
    }
}
