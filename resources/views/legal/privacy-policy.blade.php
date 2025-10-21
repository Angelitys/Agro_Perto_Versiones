<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - AgroPerto</title>
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
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Política de Privacidade</h1>
            
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Última atualização:</strong> {{ now()->format('d/m/Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Introdução</h2>
                    <p class="text-gray-700 mb-4">
                        A AgroPerto valoriza sua privacidade e está comprometida em proteger suas informações pessoais. 
                        Esta Política de Privacidade explica como coletamos, usamos, compartilhamos e protegemos suas informações 
                        quando você usa nossa plataforma.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Esta política está em conformidade com a Lei Geral de Proteção de Dados (LGPD) - Lei nº 13.709/2018.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Informações que Coletamos</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Informações Fornecidas por Você</h3>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li><strong>Dados de Cadastro:</strong> Nome, email, telefone, tipo de usuário (produtor/consumidor)</li>
                        <li><strong>Dados de Produtos:</strong> Informações sobre produtos cadastrados pelos produtores</li>
                        <li><strong>Dados de Transações:</strong> Histórico de pedidos e interações na plataforma</li>
                        <li><strong>Comunicações:</strong> Mensagens enviadas através da plataforma</li>
                        <li><strong>Avaliações:</strong> Comentários e avaliações sobre produtos e produtores</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.2 Informações Coletadas Automaticamente</h3>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li><strong>Dados de Uso:</strong> Como você interage com nossa plataforma</li>
                        <li><strong>Informações do Dispositivo:</strong> Tipo de dispositivo, sistema operacional, navegador</li>
                        <li><strong>Dados de Localização:</strong> Informações gerais de localização (não precisas)</li>
                        <li><strong>Cookies:</strong> Dados armazenados em seu navegador para melhorar a experiência</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Como Usamos suas Informações</h2>
                    <p class="text-gray-700 mb-4">Utilizamos suas informações para:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Fornecer e manter nossos serviços</li>
                        <li>Processar transações e pedidos</li>
                        <li>Facilitar a comunicação entre usuários</li>
                        <li>Enviar notificações importantes sobre pedidos</li>
                        <li>Melhorar nossa plataforma e desenvolver novos recursos</li>
                        <li>Prevenir fraudes e garantir a segurança</li>
                        <li>Cumprir obrigações legais</li>
                        <li>Enviar comunicações de marketing (com seu consentimento)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Base Legal para Processamento</h2>
                    <p class="text-gray-700 mb-4">Processamos seus dados com base em:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li><strong>Consentimento:</strong> Quando você nos dá permissão explícita</li>
                        <li><strong>Execução de Contrato:</strong> Para fornecer os serviços solicitados</li>
                        <li><strong>Interesse Legítimo:</strong> Para melhorar nossos serviços e prevenir fraudes</li>
                        <li><strong>Obrigação Legal:</strong> Para cumprir com leis aplicáveis</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Compartilhamento de Informações</h2>
                    <p class="text-gray-700 mb-4">Compartilhamos suas informações apenas quando necessário:</p>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.1 Entre Usuários da Plataforma</h3>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Nome e informações de contato para facilitar transações</li>
                        <li>Avaliações e comentários públicos</li>
                        <li>Informações necessárias para agendamento de retiradas</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.2 Com Terceiros</h3>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li><strong>Prestadores de Serviços:</strong> Para hospedagem, análise e suporte técnico</li>
                        <li><strong>Autoridades:</strong> Quando exigido por lei ou para proteger direitos</li>
                        <li><strong>Parceiros:</strong> Com seu consentimento explícito</li>
                    </ul>

                    <p class="text-gray-700 mb-4">
                        <strong>Não vendemos suas informações pessoais para terceiros.</strong>
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Segurança dos Dados</h2>
                    <p class="text-gray-700 mb-4">Implementamos medidas de segurança para proteger suas informações:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Criptografia de dados em trânsito e em repouso</li>
                        <li>Controles de acesso rigorosos</li>
                        <li>Monitoramento contínuo de segurança</li>
                        <li>Backups regulares e seguros</li>
                        <li>Treinamento de equipe em segurança de dados</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Embora implementemos medidas de segurança robustas, nenhum sistema é 100% seguro. 
                        Recomendamos que você também tome precauções para proteger suas informações.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Retenção de Dados</h2>
                    <p class="text-gray-700 mb-4">Mantemos suas informações pelo tempo necessário para:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Fornecer nossos serviços</li>
                        <li>Cumprir obrigações legais</li>
                        <li>Resolver disputas</li>
                        <li>Fazer cumprir nossos acordos</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Quando não precisarmos mais de suas informações, as excluiremos ou anonimizaremos de forma segura.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Seus Direitos</h2>
                    <p class="text-gray-700 mb-4">Você tem os seguintes direitos sobre seus dados pessoais:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li><strong>Acesso:</strong> Solicitar cópias de suas informações pessoais</li>
                        <li><strong>Retificação:</strong> Corrigir informações imprecisas ou incompletas</li>
                        <li><strong>Exclusão:</strong> Solicitar a exclusão de suas informações</li>
                        <li><strong>Portabilidade:</strong> Receber suas informações em formato estruturado</li>
                        <li><strong>Oposição:</strong> Opor-se ao processamento de suas informações</li>
                        <li><strong>Limitação:</strong> Restringir o processamento de suas informações</li>
                        <li><strong>Revogação:</strong> Retirar consentimento a qualquer momento</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Para exercer esses direitos, entre em contato conosco através dos canais indicados no final desta política.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Cookies e Tecnologias Similares</h2>
                    <p class="text-gray-700 mb-4">Utilizamos cookies para:</p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Manter você logado na plataforma</li>
                        <li>Lembrar suas preferências</li>
                        <li>Analisar o uso da plataforma</li>
                        <li>Personalizar sua experiência</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Você pode controlar cookies através das configurações do seu navegador, 
                        mas isso pode afetar a funcionalidade da plataforma.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Transferência Internacional</h2>
                    <p class="text-gray-700 mb-4">
                        Seus dados são processados principalmente no Brasil. Se houver transferência internacional, 
                        garantiremos proteção adequada através de:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 mb-4">
                        <li>Cláusulas contratuais padrão</li>
                        <li>Certificações de adequação</li>
                        <li>Outras salvaguardas legais apropriadas</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Menores de Idade</h2>
                    <p class="text-gray-700 mb-4">
                        Nossa plataforma não é destinada a menores de 18 anos. Não coletamos intencionalmente 
                        informações de menores. Se descobrirmos que coletamos dados de um menor, 
                        tomaremos medidas para excluí-los prontamente.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Alterações nesta Política</h2>
                    <p class="text-gray-700 mb-4">
                        Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos sobre 
                        mudanças significativas através da plataforma ou por email. 
                        Recomendamos que revise esta política regularmente.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Contato e Encarregado de Dados</h2>
                    <p class="text-gray-700 mb-4">
                        Para questões sobre privacidade ou para exercer seus direitos, entre em contato:
                    </p>
                    <ul class="list-none text-gray-700 mb-4">
                        <li><strong>Email:</strong> privacidade@agroperto.com.br</li>
                        <li><strong>Telefone:</strong> (11) 9999-9999</li>
                        <li><strong>Endereço:</strong> São Paulo, SP - Brasil</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Você também pode registrar uma reclamação junto à Autoridade Nacional de Proteção de Dados (ANPD).
                    </p>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500 mb-4 sm:mb-0">
                        © 2024 AgroPerto. Todos os direitos reservados.
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('legal.terms-of-service') }}" class="text-sm text-green-600 hover:text-green-700">
                            Termos de Uso
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
