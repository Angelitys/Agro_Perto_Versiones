# Guia de Instalação - AgroPerto

## Requisitos do Sistema

O sistema AgroPerto foi desenvolvido utilizando tecnologias modernas e requer os seguintes componentes para funcionar adequadamente:

**Requisitos de Software:**
- PHP 8.1 ou superior com extensões necessárias (mbstring, xml, curl, zip, gd)
- MySQL 8.0 ou superior (ou MariaDB 10.3+)
- Composer para gerenciamento de dependências PHP
- Servidor web (Apache ou Nginx)
- Node.js 16+ (opcional, apenas se desejar usar Vite)

**Requisitos de Hardware:**
- Mínimo 2GB RAM
- 5GB de espaço em disco
- Processador dual-core

## Processo de Instalação

### Passo 1: Preparação do Ambiente

Clone o repositório do projeto para o diretório desejado no servidor:

```bash
git clone [URL_DO_REPOSITORIO] agro-marketplace
cd agro-marketplace
```

### Passo 2: Instalação das Dependências

Execute o Composer para instalar todas as dependências PHP necessárias:

```bash
composer install --optimize-autoloader --no-dev
```

### Passo 3: Configuração do Ambiente

Copie o arquivo de configuração de exemplo e configure as variáveis de ambiente:

```bash
cp .env.example .env
```

Edite o arquivo `.env` com as configurações específicas do seu ambiente:

```env
APP_NAME="AgroPerto"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://seudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agroperto
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

MAIL_MAILER=smtp
MAIL_HOST=seu_servidor_email
MAIL_PORT=587
MAIL_USERNAME=seu_email
MAIL_PASSWORD=sua_senha_email
```

### Passo 4: Geração da Chave da Aplicação

Gere uma chave única para a aplicação:

```bash
php artisan key:generate
```

### Passo 5: Configuração do Banco de Dados

Execute as migrações para criar a estrutura do banco de dados:

```bash
php artisan migrate
```

Popule o banco com dados iniciais (categorias, usuários de teste):

```bash
php artisan db:seed
```

### Passo 6: Configuração de Permissões

Configure as permissões adequadas para os diretórios de armazenamento:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Passo 7: Configuração do Servidor Web

**Para Apache (.htaccess já incluído):**
Configure o DocumentRoot para apontar para a pasta `public` do projeto.

**Para Nginx:**
```nginx
server {
    listen 80;
    server_name seudominio.com;
    root /caminho/para/agro-marketplace/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Configurações Adicionais

### Configuração de Email

O sistema utiliza email para notificações. Configure o serviço SMTP no arquivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="AgroPerto"
```

### Configuração de Upload de Arquivos

O sistema permite upload de imagens de produtos. Configure os limites no PHP:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

### Configuração de Cache (Opcional)

Para melhor performance em produção, configure o cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Usuários de Teste

O sistema inclui usuários de teste para facilitar os primeiros acessos:

**Produtor de Teste:**
- Email: joao.produtor@teste.com
- Senha: 123456789
- Tipo: Produtor

**Consumidor de Teste:**
- Email: maria.consumidor@teste.com
- Senha: 123456789
- Tipo: Consumidor

## Verificação da Instalação

Após completar todos os passos, acesse o sistema através do navegador. Você deve ver a página inicial do AgroPerto com produtos em destaque e funcionalidades de navegação.

**Testes Recomendados:**
1. Acesse a página inicial e verifique se os produtos são exibidos
2. Faça login como produtor e teste o cadastro de produtos
3. Faça login como consumidor e teste o processo de compra
4. Verifique se as notificações estão funcionando
5. Teste o sistema de avaliações

## Solução de Problemas Comuns

**Erro 500 - Internal Server Error:**
- Verifique as permissões dos diretórios storage e bootstrap/cache
- Confirme se todas as variáveis do .env estão configuradas
- Verifique os logs em storage/logs/laravel.log

**Problemas de Conexão com Banco:**
- Confirme as credenciais no arquivo .env
- Verifique se o MySQL está rodando
- Teste a conexão manualmente

**Problemas com Upload de Imagens:**
- Verifique as permissões do diretório storage
- Confirme os limites de upload no PHP
- Verifique se a extensão GD está instalada

**Assets CSS/JS não carregam:**
- O sistema utiliza Tailwind CSS via CDN, não requer build
- Verifique se há bloqueios de firewall para CDNs externos

## Manutenção e Atualizações

**Backup Regular:**
Faça backup regular do banco de dados e dos arquivos de upload:

```bash
mysqldump -u usuario -p agroperto > backup_$(date +%Y%m%d).sql
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz storage/app/public/
```

**Limpeza de Logs:**
Limpe os logs periodicamente para evitar uso excessivo de disco:

```bash
php artisan log:clear
```

**Monitoramento:**
Monitore regularmente os logs de erro e performance do sistema através dos arquivos em `storage/logs/`.

## Suporte Técnico

Para questões técnicas específicas ou problemas não cobertos neste guia, consulte:
- Documentação do Laravel: https://laravel.com/docs
- Logs do sistema em storage/logs/laravel.log
- Documentação técnica nos comentários do código fonte
