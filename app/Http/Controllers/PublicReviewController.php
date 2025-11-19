<?php

namespace App\Http\Controllers;

use App\Models\PublicReview;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicReviewController extends Controller
{
    /**
     * Exibir formulário de avaliação para um pedido
     */
    public function create(Order $order)
    {
        // Verificar se o usuário pode avaliar este pedido
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para avaliar este pedido.');
        }

        if (!$order->can_review) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Este pedido ainda não pode ser avaliado. Aguarde a confirmação da retirada.');
        }

        if ($order->has_reviewed) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Você já avaliou este pedido.');
        }

        $order->load('orderItems.product.user');

        return view('reviews.create-simple', compact('order'));
    }

    /**
     * Salvar avaliação
     */
    public function store(Request $request, Order $order)
    {
        // Verificar permissões
        if ($order->user_id !== Auth::id() || !$order->can_review || $order->has_reviewed) {
            abort(403);
        }

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.product_rating' => 'required|integer|min:1|max:5',
            'reviews.*.producer_rating' => 'required|integer|min:1|max:5',
            'reviews.*.product_comment' => 'nullable|string|max:1000',
            'reviews.*.producer_comment' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            foreach ($request->reviews as $reviewData) {
                $product = Product::findOrFail($reviewData['product_id']);
                
                // Verificar se o produto está no pedido
                $orderItem = $order->orderItems()->where('product_id', $product->id)->first();
                if (!$orderItem) {
                    continue;
                }

                // Processar fotos se houver
                $photos = [];
                if ($request->hasFile("photos.{$product->id}")) {
                    foreach ($request->file("photos.{$product->id}") as $photo) {
                        $path = $photo->store('reviews', 'public');
                        $photos[] = $path;
                    }
                }

                // Criar avaliação
                PublicReview::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'producer_id' => $product->user_id,
                    'order_id' => $order->id,
                    'product_rating' => $reviewData['product_rating'],
                    'producer_rating' => $reviewData['producer_rating'],
                    'product_comment' => $reviewData['product_comment'] ?? null,
                    'producer_comment' => $reviewData['producer_comment'] ?? null,
                    'photos' => $photos,
                    'is_verified' => true,
                    'is_public' => true,
                    'reviewed_at' => now()
                ]);
            }

            // Marcar pedido como avaliado
            $order->update(['has_reviewed' => true]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Avaliações enviadas com sucesso! Obrigado pelo seu feedback.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar avaliações: ' . $e->getMessage());
        }
    }

    /**
     * Exibir avaliações de um produto
     */
    public function productReviews(Product $product)
    {
        $reviews = PublicReview::forProduct($product->id)
            ->public()
            ->verified()
            ->with(['user', 'producer'])
            ->orderBy('reviewed_at', 'desc')
            ->paginate(10);

        $averageRating = PublicReview::getProductAverageRating($product->id);
        $reviewCount = PublicReview::getProductReviewCount($product->id);

        return view('reviews.product-simple', compact('product', 'reviews', 'averageRating', 'reviewCount'));
    }

    /**
     * Exibir avaliações de um produtor
     */
    public function producerReviews(User $producer)
    {
        if ($producer->type !== 'producer') {
            abort(404);
        }

        $reviews = PublicReview::forProducer($producer->id)
            ->public()
            ->verified()
            ->with(['user', 'product'])
            ->orderBy('reviewed_at', 'desc')
            ->paginate(10);

        $averageRating = PublicReview::getProducerAverageRating($producer->id);
        $reviewCount = PublicReview::getProducerReviewCount($producer->id);

        return view('reviews.simple-producer', compact('producer', 'reviews', 'averageRating', 'reviewCount'));
    }

    /**
     * Marcar pedido como disponível para avaliação (usado quando produto é retirado)
     */
    public function enableReview(Order $order)
    {
        // Verificar se o usuário é o dono do pedido ou um admin
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->update([
            'can_review' => true,
            'picked_up_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * API para obter estatísticas de avaliações
     */
    public function getReviewStats(Request $request)
    {
        $type = $request->get('type'); // 'product' ou 'producer'
        $id = $request->get('id');

        if ($type === 'product') {
            $average = PublicReview::getProductAverageRating($id);
            $count = PublicReview::getProductReviewCount($id);
        } elseif ($type === 'producer') {
            $average = PublicReview::getProducerAverageRating($id);
            $count = PublicReview::getProducerReviewCount($id);
        } else {
            return response()->json(['error' => 'Tipo inválido'], 400);
        }

        return response()->json([
            'average_rating' => round($average, 1),
            'review_count' => $count,
            'stars' => $this->formatStars($average)
        ]);
    }

    /**
     * Formatar estrelas para exibição
     */
    private function formatStars($rating)
    {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $stars = str_repeat('★', $fullStars);
        if ($halfStar) {
            $stars .= '☆';
        }
        $stars .= str_repeat('☆', $emptyStars);

        return $stars;
    }
}
