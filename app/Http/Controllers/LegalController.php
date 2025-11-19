<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Exibir termos de uso
     */
    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    /**
     * Exibir política de privacidade
     */
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    /**
     * Exibir política de cookies
     */
    public function cookiePolicy()
    {
        return view('legal.cookie-policy');
    }

    /**
     * Exibir sobre nós
     */
    public function about()
    {
        return view('legal.about');
    }

    /**
     * Exibir contato
     */
    public function contact()
    {
        return view('legal.contact');
    }

    /**
     * Processar formulário de contato
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Aqui você pode implementar o envio de email
        // Por enquanto, vamos apenas simular
        
        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }

    /**
     * Exibir FAQ
     */
    public function faq()
    {
        $faqs = [
            [
                'category' => 'Geral',
                'questions' => [
                    [
                        'question' => 'O que é o AgroPerto?',
                        'answer' => 'O AgroPerto é uma plataforma digital que conecta produtores rurais diretamente aos consumidores, promovendo a agricultura familiar e oferecendo produtos frescos e orgânicos.'
                    ],
                    [
                        'question' => 'Como funciona a plataforma?',
                        'answer' => 'Os produtores cadastram seus produtos na plataforma, os consumidores fazem pedidos online e agendam a retirada diretamente com o produtor.'
                    ],
                    [
                        'question' => 'É gratuito para usar?',
                        'answer' => 'Sim, o cadastro e uso da plataforma são gratuitos tanto para produtores quanto para consumidores.'
                    ],
                ]
            ],
            [
                'category' => 'Para Produtores',
                'questions' => [
                    [
                        'question' => 'Como cadastro meus produtos?',
                        'answer' => 'Após criar sua conta como produtor, acesse o dashboard e clique em "Cadastrar Produto". Preencha as informações necessárias como nome, descrição, preço e estoque.'
                    ],
                    [
                        'question' => 'Como recebo os pagamentos?',
                        'answer' => 'Os pagamentos são combinados diretamente entre produtor e consumidor no momento da retirada. Futuramente implementaremos pagamentos online.'
                    ],
                    [
                        'question' => 'Posso alterar os preços dos meus produtos?',
                        'answer' => 'Sim, você pode alterar preços, estoque e outras informações dos seus produtos a qualquer momento através do dashboard.'
                    ],
                ]
            ],
            [
                'category' => 'Para Consumidores',
                'questions' => [
                    [
                        'question' => 'Como faço um pedido?',
                        'answer' => 'Navegue pelos produtos, adicione os itens desejados ao carrinho e finalize o pedido. Você receberá instruções para agendar a retirada.'
                    ],
                    [
                        'question' => 'Como funciona a retirada?',
                        'answer' => 'Após fazer o pedido, o produtor entrará em contato para agendar data, horário e local para retirada dos produtos.'
                    ],
                    [
                        'question' => 'Posso cancelar um pedido?',
                        'answer' => 'Sim, você pode cancelar pedidos que ainda não foram confirmados pelo produtor. Entre em contato através da plataforma.'
                    ],
                ]
            ],
            [
                'category' => 'Pagamentos e Entrega',
                'questions' => [
                    [
                        'question' => 'Quais formas de pagamento são aceitas?',
                        'answer' => 'Atualmente o pagamento é feito diretamente com o produtor no momento da retirada (dinheiro, PIX, cartão). Formas de pagamento online serão implementadas em breve.'
                    ],
                    [
                        'question' => 'Vocês fazem entrega?',
                        'answer' => 'No momento trabalhamos apenas com retirada agendada. Alguns produtores podem oferecer entrega mediante acordo direto.'
                    ],
                    [
                        'question' => 'E se eu não puder retirar no horário agendado?',
                        'answer' => 'Entre em contato com o produtor o quanto antes para reagendar. Evite faltas sem aviso prévio.'
                    ],
                ]
            ],
        ];

        return view('legal.faq', compact('faqs'));
    }

    /**
     * Exibir ajuda
     */
    public function help()
    {
        return view('legal.help');
    }
}
