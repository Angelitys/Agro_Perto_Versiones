<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Exibir formulário de avaliação
     */
    public function create(Order $order, Product $product)
    {
        // Verificar se o usuário é o dono do pedido
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se o produto está no pedido
        $orderItem = $order->orderItems()->where('product_id', $product->id)->first();
        if (!$orderItem) {
            abort(404, 'Produto não encontrado neste pedido.');
        }

        // Verificar se já existe avaliação
        $existingReview = Review::where([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $product->id
        ])->first();

        if ($existingReview) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Você já avaliou este produto.');
        }

        return view('reviews.create', compact('order', 'product', 'orderItem'));
    }

    /**
     * Salvar avaliação
     */
    public function store(Request $request, Order $order, Product $product)
    {
        // Verificar se o usuário é o dono do pedido
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se o produto está no pedido
        $orderItem = $order->orderItems()->where('product_id', $product->id)->first();
        if (!$orderItem) {
            abort(404);
        }

        $request->validate([
            'product_rating' => 'required|integer|min:1|max:5',
            'producer_rating' => 'required|integer|min:1|max:5',
            'product_comment' => 'nullable|string|max:1000',
            'producer_comment' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|max:2048',
            'is_public' => 'boolean'
        ]);

        // Upload das fotos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('reviews', 'public');
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'producer_id' => $product->user_id,
            'order_id' => $order->id,
            'product_rating' => $request->product_rating,
            'producer_rating' => $request->producer_rating,
            'product_comment' => $request->product_comment,
            'producer_comment' => $request->producer_comment,
            'photos' => $photos,
            'is_verified' => true, // Compra verificada
            'is_public' => $request->boolean('is_public', true)
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Avaliação enviada com sucesso!');
    }

    /**
     * Exibir avaliações de um produto
     */
    public function productReviews(Product $product)
    {
        $reviews = $product->reviews()
            ->public()
            ->with(['user', 'order'])
            ->latest()
            ->paginate(10);

        $averageRating = $product->average_rating;
        $totalReviews = $product->total_reviews;

        // Distribuição de estrelas
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $product->reviews()
                ->public()
                ->where('product_rating', $i)
                ->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0
            ];
        }

        return view('reviews.product', compact('product', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Exibir avaliações de um produtor
     */
    public function producerReviews(User $producer)
    {
        if ($producer->type !== 'producer') {
            abort(404);
        }

        $reviews = Review::where('producer_id', $producer->id)
            ->public()
            ->with(['user', 'product', 'order'])
            ->latest()
            ->paginate(10);

        $averageRating = Review::where('producer_id', $producer->id)
            ->public()
            ->avg('producer_rating') ?? 0;

        $totalReviews = Review::where('producer_id', $producer->id)
            ->public()
            ->count();

        // Distribuição de estrelas
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = Review::where('producer_id', $producer->id)
                ->public()
                ->where('producer_rating', $i)
                ->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0
            ];
        }

        return view('reviews.producer', compact('producer', 'reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Listar produtos disponíveis para avaliação
     */
    public function availableForReview()
    {
        $user = Auth::user();
        
        // Buscar pedidos entregues e confirmados que ainda não foram avaliados
        $orders = Order::where('user_id', $user->id)
            ->where('customer_confirmed', true)
            ->whereDoesntHave('reviews', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['orderItems.product'])
            ->latest()
            ->paginate(10);

        return view('reviews.available', compact('orders'));
    }

    /**
     * Minhas avaliações
     */
    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product', 'producer', 'order'])
            ->latest()
            ->paginate(10);

        return view('reviews.my-reviews', compact('reviews'));
    }
}

