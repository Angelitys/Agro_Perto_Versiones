# Guia de Deploy - AgroPerto

## Visão Geral

Este guia fornece instruções detalhadas para fazer o deploy do AgroPerto em diferentes ambientes de produção. O sistema foi desenvolvido para ser facilmente implantado em servidores tradicionais, serviços de cloud ou plataformas de hospedagem compartilhada.

## Pré-requisitos de Produção

### Requisitos do Servidor

- **Sistema Operacional**: Ubuntu 20.04+ ou CentOS 8+
- **PHP**: 8.2 ou superior com extensões necessárias
- **Servidor Web**: Apache 2.4+ ou Nginx 1.18+
- **Banco de Dados**: MariaDB 10.6+ ou MySQL 8.0+
- **Node.js**: 18+ para compilação de assets
- **Composer**: 2.0+ para gerenciamento de dependências PHP
- **SSL/TLS**: Certificado válido para HTTPS

### Extensões PHP Necessárias

```bash
# Ubuntu/Debian
sudo apt-get install php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl \
php8.2-gd php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-intl php8.2-redis

# CentOS/RHEL
sudo yum install php82-cli php82-fpm php82-mysql php82-xml php82-curl \
php82-gd php82-mbstring php82-zip php82-bcmath php82-intl php82-redis
```

## Deploy em Servidor VPS/Dedicado

### 1. Preparação do Ambiente

#### Configuração do Usuário

```bash
# Criar usuário para a aplicação
sudo adduser agromarket
sudo usermod -aG sudo agromarket
su - agromarket
```

#### Instalação do Nginx

```bash
sudo apt update
sudo apt install nginx

# Iniciar e habilitar o Nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

#### Configuração do MariaDB

```bash
sudo apt install mariadb-server
sudo mysql_secure_installation

# Criar banco de dados e usuário
sudo mysql -u root -p
```

```sql
CREATE DATABASE agromarket_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'agromarket'@'localhost' IDENTIFIED BY 'senha_super_segura';
GRANT ALL PRIVILEGES ON agromarket_prod.* TO 'agromarket'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Deploy da Aplicação

#### Clone e Configuração

```bash
cd /var/www
sudo git clone https://github.com/seu-usuario/agro-marketplace-laravel.git agromarket
sudo chown -R agromarket:agromarket /var/www/agromarket
cd /var/www/agromarket

# Instalar dependências
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

#### Configuração do Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` com as configurações de produção:

```env
APP_NAME="AgroPerto"
APP_ENV=production
APP_KEY=base64:sua_chave_gerada
APP_DEBUG=false
APP_URL=https://seu-dominio.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agromarket_prod
DB_USERNAME=agromarket
DB_PASSWORD=senha_super_segura

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=seu-smtp.com
MAIL_PORT=587
MAIL_USERNAME=noreply@seu-dominio.com
MAIL_PASSWORD=senha_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Configuração de Permissões

```bash
sudo chown -R agromarket:www-data /var/www/agromarket
sudo chmod -R 755 /var/www/agromarket
sudo chmod -R 775 /var/www/agromarket/storage
sudo chmod -R 775 /var/www/agromarket/bootstrap/cache
```

#### Migrações e Otimizações

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 3. Configuração do Nginx

Crie o arquivo de configuração do site:

```bash
sudo nano /etc/nginx/sites-available/agromarket
```

```nginx
server {
    listen 80;
    server_name seu-dominio.com www.seu-dominio.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name seu-dominio.com www.seu-dominio.com;
    root /var/www/agromarket/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    index index.php;

    charset utf-8;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/seu-dominio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/seu-dominio.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security headers
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        add_header Access-Control-Allow-Origin "*";
        add_header Cache-Control "public, max-age=31536000";
    }
}
```

Ative o site:

```bash
sudo ln -s /etc/nginx/sites-available/agromarket /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 4. Configuração do SSL com Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d seu-dominio.com -d www.seu-dominio.com
```

### 5. Configuração do PHP-FPM

Edite a configuração do pool:

```bash
sudo nano /etc/php/8.2/fpm/pool.d/agromarket.conf
```

```ini
[agromarket]
user = agromarket
group = www-data
listen = /var/run/php/php8.2-fpm-agromarket.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.process_idle_timeout = 10s
pm.max_requests = 1000

php_admin_value[upload_max_filesize] = 10M
php_admin_value[post_max_size] = 10M
php_admin_value[max_execution_time] = 300
php_admin_value[memory_limit] = 256M
```

```bash
sudo systemctl restart php8.2-fpm
```

## Deploy com Docker

### Dockerfile

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/agromarket agromarket
RUN mkdir -p /home/agromarket/.composer && \
    chown -R agromarket:agromarket /home/agromarket

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=agromarket:agromarket . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Change current user to agromarket
USER agromarket

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: agromarket
    container_name: agromarket-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - agromarket

  webserver:
    image: nginx:alpine
    container_name: agromarket-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/:/etc/nginx/conf.d/
      - ./docker/ssl/:/etc/ssl/
    networks:
      - agromarket

  db:
    image: mariadb:10.6
    container_name: agromarket-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: agromarket
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: user_password
      MYSQL_USER: agromarket
    volumes:
      - dbdata:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - agromarket

  redis:
    image: redis:alpine
    container_name: agromarket-redis
    restart: unless-stopped
    networks:
      - agromarket

networks:
  agromarket:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

## Deploy em Plataformas Cloud

### AWS Elastic Beanstalk

1. **Preparação do código:**

```bash
# Criar arquivo .ebextensions/01-packages.config
mkdir .ebextensions
cat > .ebextensions/01-packages.config << EOF
packages:
  yum:
    git: []
    nodejs: []
    npm: []
EOF
```

2. **Configurar variáveis de ambiente no console AWS**

3. **Deploy:**

```bash
eb init
eb create production
eb deploy
```

### Google Cloud Platform

```yaml
# app.yaml
runtime: php82

env_variables:
  APP_ENV: production
  APP_KEY: your-app-key
  DB_CONNECTION: mysql
  DB_HOST: your-cloud-sql-ip
  DB_DATABASE: agromarket
  DB_USERNAME: your-username
  DB_PASSWORD: your-password

automatic_scaling:
  min_instances: 1
  max_instances: 10
  target_cpu_utilization: 0.6
```

### Heroku

```bash
# Procfile
web: vendor/bin/heroku-php-apache2 public/
```

```bash
heroku create agromarket-app
heroku addons:create cleardb:ignite
heroku config:set APP_KEY=$(php artisan --show key:generate)
git push heroku main
heroku run php artisan migrate --force
```

## Monitoramento e Manutenção

### Logs

```bash
# Configurar logrotate
sudo nano /etc/logrotate.d/agromarket
```

```
/var/www/agromarket/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 agromarket agromarket
    postrotate
        /bin/systemctl reload php8.2-fpm > /dev/null 2>&1 || true
    endscript
}
```

### Backup Automatizado

```bash
#!/bin/bash
# /home/agromarket/backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/agromarket/backups"
DB_NAME="agromarket_prod"
DB_USER="agromarket"
DB_PASS="senha_super_segura"

# Criar diretório de backup
mkdir -p $BACKUP_DIR

# Backup do banco de dados
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup dos arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/agromarket/storage/app/public

# Remover backups antigos (manter apenas 7 dias)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

# Enviar para S3 (opcional)
# aws s3 cp $BACKUP_DIR/db_$DATE.sql s3://seu-bucket/backups/
# aws s3 cp $BACKUP_DIR/files_$DATE.tar.gz s3://seu-bucket/backups/
```

Configurar crontab:

```bash
crontab -e
# Backup diário às 2:00 AM
0 2 * * * /home/agromarket/backup.sh
```

### Monitoramento de Performance

```bash
# Instalar htop para monitoramento
sudo apt install htop

# Configurar New Relic (opcional)
curl -Ls https://download.newrelic.com/php_agent/scripts/newrelic-install.sh | bash
```

## Troubleshooting Comum

### Problemas de Permissão

```bash
sudo chown -R agromarket:www-data /var/www/agromarket
sudo chmod -R 755 /var/www/agromarket
sudo chmod -R 775 /var/www/agromarket/storage
sudo chmod -R 775 /var/www/agromarket/bootstrap/cache
```

### Limpeza de Cache

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
```

### Verificação de Status

```bash
# Verificar status dos serviços
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mariadb
sudo systemctl status redis

# Verificar logs
tail -f /var/log/nginx/error.log
tail -f /var/www/agromarket/storage/logs/laravel.log
```

## Checklist de Deploy

- [ ] Servidor configurado com requisitos mínimos
- [ ] Banco de dados criado e configurado
- [ ] Código clonado e dependências instaladas
- [ ] Arquivo .env configurado para produção
- [ ] Migrações executadas
- [ ] Storage linkado
- [ ] Permissões configuradas corretamente
- [ ] Nginx/Apache configurado
- [ ] SSL configurado
- [ ] Cache otimizado
- [ ] Backup configurado
- [ ] Monitoramento ativo
- [ ] Testes de funcionalidade realizados

Este guia fornece uma base sólida para deploy em produção. Ajuste as configurações conforme suas necessidades específicas e ambiente de hospedagem.

