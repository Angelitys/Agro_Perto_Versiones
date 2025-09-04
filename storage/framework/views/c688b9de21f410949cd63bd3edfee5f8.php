<?php $__env->startSection('title', 'Produtos - AgroPerto'); ?>
<?php $__env->startSection('description', 'Explore todos os produtos frescos da agricultura familiar disponíveis no AgroPerto'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Todos os Produtos</h1>
            <p class="text-gray-600">Descubra produtos frescos da agricultura familiar</p>
        </div>
        
        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('products.create')); ?>" class="mt-4 md:mt-0 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
            Vender Produto
        </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="<?php echo e(route('products.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Nome do produto..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select 
                    id="category" 
                    name="category"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                    <option value="">Todas as categorias</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1">Preço máximo</label>
                <input 
                    type="number" 
                    id="price_max" 
                    name="price_max" 
                    value="<?php echo e(request('price_max')); ?>"
                    placeholder="R$ 0,00"
                    step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <?php if($products->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                <!-- Product Image -->
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    <?php if($product->first_image): ?>
                        <img src="<?php echo e(Storage::url($product->first_image)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <!-- Category and Stock -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                            <?php echo e($product->category->name); ?>

                        </span>
                        <span class="text-xs text-gray-500">
                            <?php echo e($product->stock_quantity); ?> <?php echo e($product->unit); ?>

                        </span>
                    </div>
                    
                    <!-- Product Name -->
                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                        <?php echo e($product->name); ?>

                    </h3>
                    
                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        <?php echo e(Str::limit($product->description, 80)); ?>

                    </p>
                    
                    <!-- Price and Action -->
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-green-600">
                                R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?>

                            </span>
                            <span class="text-xs text-gray-500">/ <?php echo e($product->unit); ?></span>
                        </div>
                        <a href="<?php echo e(route('products.show', $product)); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors">
                            Ver Detalhes
                        </a>
                    </div>
                    
                    <!-- Producer and Location -->
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <?php echo e($product->user->name); ?>

                        </div>
                        <?php if($product->origin): ?>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo e($product->origin); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            <?php echo e($products->links()); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-500 mb-6">Tente ajustar os filtros ou explore outras categorias.</p>
            <a href="<?php echo e(route('products.index')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Ver Todos os Produtos
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.marketplace', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\angel\Desktop\agro-marketplace-final\resources\views/products/index.blade.php ENDPATH**/ ?>