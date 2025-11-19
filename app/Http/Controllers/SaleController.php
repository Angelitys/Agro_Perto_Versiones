<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Exibe o formulário para registrar uma venda na feira (baixa de estoque).
     */
    public function create()
    {
        if (Auth::user()->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        $products = Product::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create-fair-sale', compact('products'));
    }

    /**
     * Processa o registro da venda na feira (baixa de estoque).
     */
    public function store(Request $request)
    {
        if (Auth::user()->type !== 'producer') {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_type' => 'required|in:fair,manual',
            'notes' => 'nullable|string|max:255',
        ]);

        $product = Product::where('user_id', Auth::id())
            ->findOrFail($validated['product_id']);

        // Inicia a transação para garantir a integridade
        DB::beginTransaction();

        try {
            // 1. Atualiza o estoque
            $product->stock_quantity -= $validated['quantity'];
            if ($product->stock_quantity < 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Estoque insuficiente para a venda.');
            }
            $product->save();

            // 2. Registra a venda (simulação de registro de venda manual para o dashboard)
            // Em um sistema real, isso criaria um registro na tabela 'sales' ou 'transactions'
            // Aqui, apenas atualizamos o estoque e registramos um log para simular a venda.
            
            // Log::info('Venda na feira registrada', [
            //     'producer_id' => Auth::id(),
            //     'product_id' => $product->id,
            //     'quantity' => $validated['quantity'],
            //     'type' => $validated['sale_type']
            // ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Venda na feira registrada e estoque atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao registrar a venda: ' . $e->getMessage());
        }
    }
}

