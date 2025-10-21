<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <i class="fas fa-leaf text-green-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">AgroPerto</span>
                        <span class="text-sm text-gray-500 ml-2">Agricultura Familiar</span>
                    </a>
                </div>
                
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-basket mr-1"></i> Produtos
                    </a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium relative">
                            <i class="fas fa-shopping-cart mr-1"></i> Carrinho
                            <span class="absolute -top-1 -right-1 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ auth()->user()->cart ? auth()->user()->cart->items->count() : 0 }}
                            </span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-1"></i> Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Entrar
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                            <i class="fas fa-user-plus mr-1"></i> Cadastrar
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-gray-900">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Cadastrar Produto</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header da página -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                Cadastrar Novo Produto
            </h1>
            <p class="text-gray-600 mt-2">
                Adicione um novo produto à sua loja virtual
            </p>
        </div>

        <!-- Formulário -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome do Produto -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Produto *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name') }}"
                               placeholder="Ex: Tomate Orgânico, Alface Crespa, Mel Silvestre"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria *
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preço -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Preço (R$) *
                        </label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required
                               value="{{ old('price') }}"
                               placeholder="0,00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unidade -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            Unidade *
                        </label>
                        <select id="unit" name="unit" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Selecione a unidade</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Grama (g)</option>
                            <option value="unidade" {{ old('unit') == 'unidade' ? 'selected' : '' }}>Unidade</option>
                            <option value="litro" {{ old('unit') == 'litro' ? 'selected' : '' }}>Litro</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                            <option value="pacote" {{ old('unit') == 'pacote' ? 'selected' : '' }}>Pacote</option>
                            <option value="caixa" {{ old('unit') == 'caixa' ? 'selected' : '' }}>Caixa</option>
                        </select>
                        @error('unit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estoque -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantidade em Estoque *
                        </label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" required
                               value="{{ old('stock_quantity') }}"
                               placeholder="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('stock_quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição do Produto *
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  placeholder="Descreva seu produto: origem, método de cultivo, características, benefícios..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Origem -->
                    <div class="md:col-span-2">
                        <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">
                            Origem/Local de Produção
                        </label>
                        <input type="text" id="origin" name="origin"
                               value="{{ old('origin') }}"
                               placeholder="Ex: Fazenda Orgânica, Campinas - SP"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('origin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data da Colheita -->
                    <div>
                        <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Data da Colheita
                        </label>
                        <input type="date" id="harvest_date" name="harvest_date"
                               value="{{ old('harvest_date') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('harvest_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagem -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto do Produto
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">
                            Formatos aceitos: JPG, PNG. Tamanho máximo: 2MB
                        </p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Produto ativo (disponível para venda)</span>
                    </label>
                </div>

                <!-- Botões -->
                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('dashboard') }}" 
                       class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar ao Dashboard
                    </a>
                    
                    <button type="submit" 
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Cadastrar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-leaf text-green-400 text-2xl mr-2"></i>
                <span class="text-xl font-bold">AgroPerto</span>
            </div>
            <p class="text-gray-400">Conectando produtores rurais diretamente aos consumidores</p>
            <p class="text-gray-500 text-sm mt-2">© 2024 AgroPerto. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>