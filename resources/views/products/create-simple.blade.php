@extends('layouts.app-simple')

@section('title', 'Cadastrar Produto - AgroPerto')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cadastrar Novo Produto</h1>
                <p class="text-gray-600 mt-1">Adicione um novo produto ao seu catálogo</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <!-- Informações Básicas -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    Informações Básicas
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome do Produto -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Produto *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                            placeholder="Ex: Tomate Orgânico, Alface Crespa..."
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria *
                        </label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('category_id') border-red-500 @enderror"
                        >
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Origem -->
                    <div>
                        <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">
                            Origem
                        </label>
                        <input 
                            type="text" 
                            id="origin" 
                            name="origin" 
                            value="{{ old('origin') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('origin') border-red-500 @enderror"
                            placeholder="Ex: Fazenda Orgânica, Campinas - SP"
                        >
                        @error('origin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição *
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror"
                        placeholder="Descreva seu produto: características, benefícios, forma de cultivo..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Preço e Estoque -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                    Preço e Estoque
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Preço -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Preço (R$) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price') }}"
                                step="0.01" 
                                min="0" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('price') border-red-500 @enderror"
                                placeholder="0,00"
                            >
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantidade em Estoque -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantidade *
                        </label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity') }}"
                            min="0" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('stock_quantity') border-red-500 @enderror"
                            placeholder="0"
                        >
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unidade -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            Unidade *
                        </label>
                        <select 
                            id="unit" 
                            name="unit" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('unit') border-red-500 @enderror"
                        >
                            <option value="">Selecione</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Grama (g)</option>
                            <option value="unidade" {{ old('unit') == 'unidade' ? 'selected' : '' }}>Unidade</option>
                            <option value="maço" {{ old('unit') == 'maço' ? 'selected' : '' }}>Maço</option>
                            <option value="litro" {{ old('unit') == 'litro' ? 'selected' : '' }}>Litro</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data de Colheita -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Informações Adicionais
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data de Colheita -->
                    <div>
                        <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Data de Colheita
                        </label>
                        <input 
                            type="date" 
                            id="harvest_date" 
                            name="harvest_date" 
                            value="{{ old('harvest_date') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('harvest_date') border-red-500 @enderror"
                        >
                        @error('harvest_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Imagens -->
            <div class="pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-images text-green-600 mr-2"></i>
                    Imagens do Produto
                </h2>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors">
                    <input 
                        type="file" 
                        id="images" 
                        name="images[]" 
                        multiple 
                        accept="image/*"
                        class="hidden"
                        onchange="previewImages(this)"
                    >
                    <label for="images" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700">Clique para adicionar imagens</p>
                        <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF até 2MB cada</p>
                    </label>
                    
                    <!-- Preview das imagens -->
                    <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                </div>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg font-medium">
                    <i class="fas fa-save mr-2"></i>Cadastrar Produto
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <span class="text-white text-xs font-medium">Imagem ${index + 1}</span>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
}

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'description', 'price', 'stock_quantity', 'unit', 'category_id'];
    let hasError = false;
    
    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (!input.value.trim()) {
            hasError = true;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});
</script>
@endpush
@endsection
