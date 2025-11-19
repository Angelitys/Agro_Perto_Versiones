<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display products of the authenticated producer.
     */
    public function myProducts(Request $request)
    {
        // Verificar se o usuário está logado e é produtor
        if (!auth()->check() || auth()->user()->type !== 'producer') {
            return redirect()->route('products.index')->with('error', 'Acesso negado.');
        }

        $query = Product::where('user_id', auth()->id())
            ->with(['category', 'user']);

        // Filtro por categoria
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtro por busca
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);
        $categories = Category::active()->get();
        
        return view('products.simple-index', compact('products', 'categories'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::active()
            ->inStock()
            ->with(['category', 'user']);

        // Filtro por categoria
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtro por busca
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);
        $categories = Category::active()->get();
        
        return view('products.simple-index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 🔹 Permitir acesso para teste
        $categories = Category::all();

        // 🔹 Verificar login e tipo de usuário para produção
        if (!auth()->check()) {
            session()->flash('error', 'Você precisa estar logado para cadastrar produtos.');
        } elseif (auth()->user()->type !== 'producer') {
            session()->flash('error', 'Apenas produtores podem cadastrar produtos.');
        }

        return view('products.create-simple', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar se o usuário está logado e é produtor
        if (!auth()->check() || auth()->user()->type !== 'producer') {
            return redirect()->route('login')->with('error', 'Apenas produtores podem cadastrar produtos.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'harvest_date' => 'nullable|date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = auth()->id();

        // Processar imagens se houver
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'user']);

        $relatedProducts = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.simple-show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::active()->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'harvest_date' => 'nullable|date',
            'active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produto removido com sucesso!');
    }

    /**
     * Listar produtos por categoria
     */
    public function byCategory(Category $category)
    {
        $products = $category->products()
            ->active()
            ->inStock()
            ->with(['category', 'user'])
            ->paginate(12);

        $categories = Category::active()->get();

        return view('products.by-category', compact('products', 'categories', 'category'));
    }
}
