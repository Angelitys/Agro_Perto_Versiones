<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo - Funcionalidades AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="demoApp()">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-green-600 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold">🌱 AgroPerto - Demonstração de Funcionalidades</h1>
                <p class="text-green-100 mt-2">Sistema completo de marketplace para produtos agrícolas</p>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex space-x-8 py-4">
                    <button @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500'"
                            class="border-b-2 pb-2 px-1 font-medium">
                        📊 Visão Geral
                    </button>
                    <button @click="activeTab = 'consumer'" 
                            :class="activeTab === 'consumer' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500'"
                            class="border-b-2 pb-2 px-1 font-medium">
                        🛒 Consumidor
                    </button>
                    <button @click="activeTab = 'producer'" 
                            :class="activeTab === 'producer' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500'"
                            class="border-b-2 pb-2 px-1 font-medium">
                        🌱 Produtor
                    </button>
                    <button @click="activeTab = 'api'" 
                            :class="activeTab === 'api' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500'"
                            class="border-b-2 pb-2 px-1 font-medium">
                        🔧 API & Testes
                    </button>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" class="space-y-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🎯 Funcionalidades Implementadas</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-green-600">✅ Funcionalidades Básicas</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Registo e login de utilizadores</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Separação produtores/consumidores</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Gestão de produtos</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Sistema de categorias</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Carrinho de compras</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Processamento de pedidos</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-green-600">🚀 Funcionalidades Avançadas</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Pesquisa e filtros</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Agendamento de recolha</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Sistema de avaliações</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Dashboard para produtores</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Vendas na feira</li>
                                <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Confirmação de entrega</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quick Access -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🌐</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Sistema Principal</h3>
                        <p class="text-gray-600 text-sm mb-4">Interface completa do marketplace</p>
                        <a href="index-xampp.php" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                            Abrir Sistema
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🔧</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Configuração</h3>
                        <p class="text-gray-600 text-sm mb-4">Setup automático da base de dados</p>
                        <a href="setup-database.php" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                            Configurar BD
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🧪</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Testes</h3>
                        <p class="text-gray-600 text-sm mb-4">Validação completa da API</p>
                        <a href="test-api-complete.php" target="_blank" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
                            Executar Testes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Consumer Tab -->
            <div x-show="activeTab === 'consumer'" class="space-y-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🛒 Funcionalidades para Consumidores</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📝 Registo e Login</h3>
                                <p class="text-gray-600 text-sm">Criar conta como consumidor e aceder ao sistema com credenciais seguras.</p>
                            </div>
                            
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">🔍 Pesquisa de Produtos</h3>
                                <p class="text-gray-600 text-sm">Procurar produtos por nome, categoria ou produtor com filtros avançados.</p>
                            </div>
                            
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">🛒 Carrinho de Compras</h3>
                                <p class="text-gray-600 text-sm">Adicionar produtos ao carrinho e gerir quantidades antes da compra.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📦 Gestão de Pedidos</h3>
                                <p class="text-gray-600 text-sm">Fazer pedidos, agendar recolha e acompanhar o status da encomenda.</p>
                            </div>
                            
                            <div class="border-l-4 border-red-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">⭐ Sistema de Avaliações</h3>
                                <p class="text-gray-600 text-sm">Avaliar produtos comprados e ver avaliações de outros consumidores.</p>
                            </div>
                            
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📍 Localização de Feiras</h3>
                                <p class="text-gray-600 text-sm">Ver onde e quando os produtos estão disponíveis nas feiras locais.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-green-50 rounded-lg">
                        <h4 class="font-semibold text-green-800 mb-2">👤 Contas de Teste - Consumidores</h4>
                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong>Maria Santos</strong><br>
                                Email: maria@consumidor.com<br>
                                Senha: password
                            </div>
                            <div>
                                <strong>Ana Costa</strong><br>
                                Email: ana@consumidor.com<br>
                                Senha: password
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Producer Tab -->
            <div x-show="activeTab === 'producer'" class="space-y-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🌱 Funcionalidades para Produtores</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📊 Dashboard de Vendas</h3>
                                <p class="text-gray-600 text-sm">Painel completo com estatísticas de vendas, produtos mais vendidos e receitas.</p>
                            </div>
                            
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">🥕 Gestão de Produtos</h3>
                                <p class="text-gray-600 text-sm">Adicionar, editar e remover produtos com informações detalhadas.</p>
                            </div>
                            
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📦 Gestão de Pedidos</h3>
                                <p class="text-gray-600 text-sm">Ver pedidos recebidos, confirmar disponibilidade e gerir entregas.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">🏪 Vendas na Feira</h3>
                                <p class="text-gray-600 text-sm">Registar vendas diretas na feira e gerir stock em tempo real.</p>
                            </div>
                            
                            <div class="border-l-4 border-red-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📅 Agendamento</h3>
                                <p class="text-gray-600 text-sm">Definir disponibilidade de produtos por data e localização da feira.</p>
                            </div>
                            
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">📈 Relatórios</h3>
                                <p class="text-gray-600 text-sm">Relatórios detalhados de vendas, produtos e desempenho.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-green-50 rounded-lg">
                        <h4 class="font-semibold text-green-800 mb-2">🌱 Contas de Teste - Produtores</h4>
                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong>João Silva</strong><br>
                                Email: joao@produtor.com<br>
                                Senha: password<br>
                                <em>Quinta do João - Santarém</em>
                            </div>
                            <div>
                                <strong>Pedro Oliveira</strong><br>
                                Email: pedro@produtor.com<br>
                                Senha: password<br>
                                <em>Pomar do Pedro - Óbidos</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Tab -->
            <div x-show="activeTab === 'api'" class="space-y-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🔧 API e Testes do Sistema</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">🧪 Testes Disponíveis</h3>
                            
                            <div class="space-y-4">
                                <div class="p-4 border rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-2">Teste de Conexão</h4>
                                    <p class="text-gray-600 text-sm mb-3">Verifica conexão com a base de dados e lista tabelas.</p>
                                    <a href="teste-conexao-xampp.php" target="_blank" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        Executar
                                    </a>
                                </div>
                                
                                <div class="p-4 border rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-2">Teste Completo da API</h4>
                                    <p class="text-gray-600 text-sm mb-3">20 testes abrangentes de todas as funcionalidades.</p>
                                    <a href="test-api-complete.php" target="_blank" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700">
                                        Executar
                                    </a>
                                </div>
                                
                                <div class="p-4 border rounded-lg">
                                    <h4 class="font-semibold text-gray-900 mb-2">Status da API</h4>
                                    <p class="text-gray-600 text-sm mb-3">Verifica se a API está online e funcionando.</p>
                                    <a href="api-xampp.php/status" target="_blank" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                        Verificar
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">📡 Endpoints da API</h3>
                            
                            <div class="space-y-2 text-sm font-mono bg-gray-50 p-4 rounded-lg">
                                <div><span class="text-green-600">GET</span> /status</div>
                                <div><span class="text-green-600">GET</span> /categories</div>
                                <div><span class="text-green-600">GET</span> /products</div>
                                <div><span class="text-green-600">GET</span> /products/{id}</div>
                                <div><span class="text-blue-600">POST</span> /register</div>
                                <div><span class="text-blue-600">POST</span> /login</div>
                                <div><span class="text-blue-600">POST</span> /products</div>
                                <div><span class="text-blue-600">POST</span> /orders</div>
                                <div><span class="text-blue-600">POST</span> /cart/add</div>
                                <div><span class="text-green-600">GET</span> /cart/{user_id}</div>
                                <div><span class="text-blue-600">POST</span> /reviews</div>
                                <div><span class="text-green-600">GET</span> /products/search</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h3 class="text-lg font-semibold mb-2">🌱 AgroPerto Marketplace</h3>
                <p class="text-gray-400 mb-4">Sistema completo para conectar produtores e consumidores</p>
                <div class="flex justify-center space-x-6 text-sm">
                    <span>✅ Base de Dados: MariaDB</span>
                    <span>✅ Backend: PHP</span>
                    <span>✅ Frontend: HTML/CSS/JS</span>
                    <span>✅ Status: Pronto para Produção</span>
                </div>
                <p class="text-gray-500 text-sm mt-4">Versão Final - 07/10/2025</p>
            </div>
        </footer>
    </div>

    <script>
        function demoApp() {
            return {
                activeTab: 'overview'
            }
        }
    </script>
</body>
</html>
