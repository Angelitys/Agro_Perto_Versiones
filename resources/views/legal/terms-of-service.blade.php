<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - AgroPerto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">AgroPerto</h1>
                            <p class="text-xs text-gray-500">Agricultura Familiar</p>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Voltar ao Início
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Termos de Uso</h1>
            
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Última atualização:</strong> {{ now()->format('d/m/Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Aceitação dos Termos</h2>
                    <p class="text-gray-700 mb-4">
                        Ao acessar e usar a plataforma AgroPerto, você concorda em cumprir e estar vinculado a estes Termos de Uso. 
                        Se você não concordar com qualquer parte destes termos, não deve usar nossos serviços.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Descrição do Serviço</h2>
                    <p class="text-gray-700 mb-4">
                        O AgroPerto é uma plataforma digital que conecta produtores rurais diretamente aos consumidores, 
                        facilitando a venda de produtos da agricultura familiar. Nossa plataforma permite:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Cadastro de produtores e seus produtos</li>
                        <li>Navegação e compra de produtos pelos consumidores</li>
                        <li>Agendamento de retirada de produtos</li>
                        <li>Sistema de avaliações e feedback</li>
                        <li>Comunicação entre produtores e consumidores</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Cadastro e Conta de Usuário</h2>
                    <p class="text-gray-700 mb-4">
                        Para usar nossos serviços, você deve criar uma conta fornecendo informações precisas e atualizadas. 
                        Você é responsável por:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Manter a confidencialidade de sua senha</li>
                        <li>Todas as atividades que ocorrem em sua conta</li>
                        <li>Notificar-nos imediatamente sobre qualquer uso não autorizado</li>
                        <li>Fornecer informações verdadeiras e atualizadas</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Responsabilidades dos Produtores</h2>
                    <p class="text-gray-700 mb-4">Os produtores se comprometem a:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Fornecer informações precisas sobre seus produtos</li>
                        <li>Manter a qualidade e segurança dos produtos oferecidos</li>
                        <li>Cumprir com as legislações sanitárias e de segurança alimentar</li>
                        <li>Honrar os pedidos confirmados e prazos acordados</li>
                        <li>Comunicar-se de forma respeitosa com os consumidores</li>
                        <li>Manter estoque atualizado na plataforma</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Responsabilidades dos Consumidores</h2>
                    <p class="text-gray-700 mb-4">Os consumidores se comprometem a:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Realizar pedidos de boa fé</li>
                        <li>Comparecer nos horários agendados para retirada</li>
                        <li>Comunicar cancelamentos com antecedência</li>
                        <li>Tratar produtores com respeito e cordialidade</li>
                        <li>Fornecer feedback construtivo através das avaliações</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Transações e Pagamentos</h2>
                    <p class="text-gray-700 mb-4">
                        Atualmente, as transações financeiras ocorrem diretamente entre produtores e consumidores. 
                        O AgroPerto não processa pagamentos, mas facilita a comunicação para acordos comerciais.
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Preços são definidos pelos produtores</li>
                        <li>Formas de pagamento são acordadas entre as partes</li>
                        <li>O AgroPerto não se responsabiliza por disputas de pagamento</li>
                        <li>Recomendamos pagamentos seguros (PIX, cartão)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Política de Cancelamento</h2>
                    <p class="text-gray-700 mb-4">
                        Pedidos podem ser cancelados até que sejam confirmados pelo produtor. 
                        Após a confirmação, cancelamentos devem ser acordados diretamente entre as partes.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Propriedade Intelectual</h2>
                    <p class="text-gray-700 mb-4">
                        Todo o conteúdo da plataforma AgroPerto, incluindo textos, gráficos, logos, ícones, imagens, 
                        clipes de áudio, downloads digitais e software, é propriedade do AgroPerto ou de seus fornecedores 
                        de conteúdo e está protegido por leis de direitos autorais.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Limitação de Responsabilidade</h2>
                    <p class="text-gray-700 mb-4">
                        O AgroPerto atua como intermediário entre produtores e consumidores. Não nos responsabilizamos por:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Qualidade, segurança ou legalidade dos produtos</li>
                        <li>Veracidade das informações fornecidas pelos usuários</li>
                        <li>Disputas entre produtores e consumidores</li>
                        <li>Danos decorrentes do uso da plataforma</li>
                        <li>Interrupções temporárias do serviço</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Conduta Proibida</h2>
                    <p class="text-gray-700 mb-4">É proibido usar a plataforma para:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Atividades ilegais ou fraudulentas</li>
                        <li>Venda de produtos proibidos ou perigosos</li>
                        <li>Spam ou comunicações não solicitadas</li>
                        <li>Violação de direitos de terceiros</li>
                        <li>Interferência no funcionamento da plataforma</li>
                        <li>Criação de contas falsas ou múltiplas</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Modificações dos Termos</h2>
                    <p class="text-gray-700 mb-4">
                        Reservamo-nos o direito de modificar estes termos a qualquer momento. 
                        As alterações entrarão em vigor imediatamente após a publicação na plataforma. 
                        O uso continuado dos serviços constitui aceitação dos termos modificados.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Encerramento</h2>
                    <p class="text-gray-700 mb-4">
                        Podemos encerrar ou suspender sua conta e acesso aos serviços imediatamente, 
                        sem aviso prévio, por qualquer motivo, incluindo violação destes Termos de Uso.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Lei Aplicável</h2>
                    <p class="text-gray-700 mb-4">
                        Estes Termos de Uso são regidos pelas leis brasileiras. 
                        Qualquer disputa será resolvida nos tribunais competentes do Brasil.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">14. Contato</h2>
                    <p class="text-gray-700 mb-4">
                        Se você tiver dúvidas sobre estes Termos de Uso, entre em contato conosco:
                    </p>
                    <ul class="list-none text-gray-700">
                        <li><strong>Email:</strong> contato@agroperto.com.br</li>
                        <li><strong>Telefone:</strong> (11) 9999-9999</li>
                        <li><strong>Endereço:</strong> São Paulo, SP - Brasil</li>
                    </ul>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500 mb-4 sm:mb-0">
                        © 2024 AgroPerto. Todos os direitos reservados.
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('legal.privacy-policy') }}" class="text-sm text-green-600 hover:text-green-700">
                            Política de Privacidade
                        </a>
                        <a href="{{ route('legal.contact') }}" class="text-sm text-green-600 hover:text-green-700">
                            Contato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
