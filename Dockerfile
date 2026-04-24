FROM php:8.4-fpm

ENV TZ=America/Monterrey

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    libonig-dev \
    libicu-dev \
    nginx \
    supervisor \
    cron \
    tzdata \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && docker-php-ext-install pdo pdo_mysql mbstring zip pcntl bcmath intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY backend/ .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

RUN echo "* * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel
RUN chmod 0644 /etc/cron.d/laravel
RUN crontab /etc/cron.d/laravel
RUN touch /var/log/cron.log

RUN rm /etc/nginx/sites-enabled/default || true
COPY backend/docker/nginx.conf /etc/nginx/sites-available/laravel.conf
RUN ln -s /etc/nginx/sites-available/laravel.conf /etc/nginx/sites-enabled/

COPY backend/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80 443 6001 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]