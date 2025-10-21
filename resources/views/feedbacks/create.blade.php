@extends('layouts.marketplace')

@section('title', 'Enviar Feedback - AgroPerto')
@section('description', 'Deixe seu feedback sobre o produto e o produtor no AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Enviar Feedback para o Pedido #{{ $order->id }}</h1>

        <form action="{{ route('feedbacks.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700">Avaliação (1-5 estrelas)</label>
                <select name="rating" id="rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                    <option value="">Selecione uma avaliação</option>
                    <option value="5">5 Estrelas - Excelente</option>
                    <option value="4">4 Estrelas - Muito Bom</option>
                    <option value="3">3 Estrelas - Bom</option>
                    <option value="2">2 Estrelas - Regular</option>
                    <option value="1">1 Estrela - Ruim</option>
                </select>
                @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="comment" class="block text-sm font-medium text-gray-700">Comentário (Opcional)</label>
                <textarea name="comment" id="comment" rows="5" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Deixe seu comentário sobre o produto e o produtor..."></textarea>
                @error('comment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    Enviar Feedback
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

