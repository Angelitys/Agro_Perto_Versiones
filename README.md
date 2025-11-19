# AgroPerto - Sistema de Marketplace Agrícola

## 🚀 Instalação Rápida

### 1. Extrair o Projeto
```bash
unzip agroperto-pronto.zip
cd agroperto-pronto
```

### 2. Inicializar (Automático)
```bash
./inicializar.sh
```

### 3. Iniciar o Servidor
```bash
php artisan serve
```

### 4. Acessar o Sistema
Abra seu navegador em: http://localhost:8000

## 👥 Usuários de Teste

**Produtor:**
- Email: joao.produtor@teste.com
- Senha: 123456789

**Consumidor:**
- Email: maria.consumidor@teste.com
- Senha: 123456789

## 📋 Requisitos

- PHP 8.1+
- MySQL 8.0+
- Composer
- Extensões PHP: mbstring, xml, curl, zip, gd, pdo_mysql

## 🔧 Configuração Manual

Se a inicialização automática não funcionar:

### 1. Configurar Banco de Dados
Edite o arquivo `.env` com suas credenciais MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agroperto
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 2. Criar Banco
```sql
CREATE DATABASE agroperto;
```

### 3. Executar Migrações
```bash
php artisan migrate
php artisan db:seed
```

## 🌟 Funcionalidades

- ✅ Cadastro de produtores e consumidores
- ✅ Catálogo de produtos com busca e filtros
- ✅ Carrinho de compras
- ✅ Checkout com seleção de horário de retirada
- ✅ Sistema de pedidos
- ✅ Notificações para produtores
- ✅ Sistema de avaliações públicas
- ✅ Dashboard responsivo
- ✅ Interface moderna com Tailwind CSS

## 🛠️ Tecnologias

- **Backend:** Laravel 10.x
- **Frontend:** Blade + Tailwind CSS
- **Banco:** MySQL
- **Autenticação:** Laravel Breeze

## 📞 Suporte

Para problemas ou dúvidas:
1. Verifique os logs em `storage/logs/laravel.log`
2. Consulte a documentação em `MELHORIAS_FINALIZADAS.md`
3. Use o script de diagnóstico `fix_error_500.sh`

## 🎯 Próximos Passos

Após a instalação:
1. Teste o cadastro de produtos como produtor
2. Teste o processo de compra como consumidor
3. Configure email SMTP para notificações
4. Personalize as cores e layout conforme necessário
