@extends('layouts.app')

@section('title', 'Editar Produto - AgroPerto')
@section('description', 'Edite as informações do seu produto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Produto</h1>
        <p class="text-gray-600">Atualize as informações do seu produto</p>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Informações Básicas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome do Produto *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $product->name) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Ex: Tomates Orgânicos"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Categoria *
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                        Preço (R$) *
                    </label>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', $product->price) }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price') border-red-500 @enderror"
                        placeholder="0,00"
                    >
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                        Quantidade em Estoque *
                    </label>
                    <input 
                        type="number" 
                        id="stock_quantity" 
                        name="stock_quantity" 
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        min="0"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                        placeholder="0"
                    >
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">
                        Unidade de Medida *
                    </label>
                    <select 
                        id="unit" 
                        name="unit" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('unit') border-red-500 @enderror"
                    >
                        <option value="">Selecione a unidade</option>
                        <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                        <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Grama (g)</option>
                        <option value="unidade" {{ old('unit', $product->unit) == 'unidade' ? 'selected' : '' }}>Unidade</option>
                        <option value="maço" {{ old('unit', $product->unit) == 'maço' ? 'selected' : '' }}>Maço</option>
                        <option value="dúzia" {{ old('unit', $product->unit) == 'dúzia' ? 'selected' : '' }}>Dúzia</option>
                        <option value="litro" {{ old('unit', $product->unit) == 'litro' ? 'selected' : '' }}>Litro</option>
                    </select>
                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Descrição *
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Descreva seu produto, suas características, benefícios..."
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Informações Adicionais</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Origin -->
                <div>
                    <label for="origin" class="block text-sm font-medium text-gray-700 mb-1">
                        Origem/Localidade
                    </label>
                    <input 
                        type="text" 
                        id="origin" 
                        name="origin" 
                        value="{{ old('origin', $product->origin) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('origin') border-red-500 @enderror"
                        placeholder="Ex: Fazenda São João, Minas Gerais"
                    >
                    @error('origin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harvest Date -->
                <div>
                    <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Data da Colheita
                    </label>
                    <input 
                        type="date" 
                        id="harvest_date" 
                        name="harvest_date" 
                        value="{{ old('harvest_date', $product->harvest_date ? $product->harvest_date->format('Y-m-d') : '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('harvest_date') border-red-500 @enderror"
                    >
                    @error('harvest_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="active" 
                            value="1"
                            {{ old('active', $product->active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        >
                        <span class="ml-2 text-sm text-gray-700">Produto ativo (visível para clientes)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Current Images -->
        @if($product->images && count($product->images) > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Imagens Atuais</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Imagem do produto" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg"></div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Product Images -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ $product->images && count($product->images) > 0 ? 'Substituir Imagens' : 'Adicionar Imagens' }}</h2>
            
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                    Fotos do Produto
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Carregar fotos</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">ou arraste e solte</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB cada</p>
                        <p class="text-xs text-gray-400">Deixe em branco para manter as imagens atuais</p>
                    </div>
                </div>
                
                <!-- Preview das imagens selecionadas -->
                <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
                    <!-- As imagens aparecerão aqui -->
                </div>
                
                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('products.show', $product) }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                Atualizar Produto
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview selected images
    document.getElementById('images').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('image-preview');
        
        // Limpar preview anterior
        previewContainer.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'relative group';
                        
                        imageDiv.innerHTML = `
                            <img src="${e.target.result}" 
                                 alt="Preview ${index + 1}" 
                                 class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                    ${file.name}
                                </span>
                            </div>
                        `;
                        
                        previewContainer.appendChild(imageDiv);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
            
            console.log(`${files.length} imagem(ns) selecionada(s)`);
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endpush

