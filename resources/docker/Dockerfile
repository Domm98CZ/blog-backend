FROM php:8.4-apache-bookworm

# update debian & get required packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    p7zip-full \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev

# set apache site
COPY ./apache.conf /etc/apache2/sites-available/apache_http.conf
RUN cd /etc/apache2/sites-available && a2dissite --quiet * \
    && a2enmod --quiet rewrite \
    && a2ensite --quiet apache_http.conf

# php extensions install
RUN docker-php-ext-install \
    zip \
	pdo \
	pdo_sqlite

# add composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# set max memory_limit: 128M
RUN echo "memory_limit=128M" >> "$PHP_INI_DIR/php.ini"

# set max_execution_time: 30s
RUN echo "max_execution_time=30" >> "$PHP_INI_DIR/php.ini"

# add rights for nette dirs
RUN mkdir /var/www/app /var/www/app/temp /var/www/app/log
RUN chmod -R 777 /var/www/app/temp /var/www/app/log

# clean
RUN apt-get clean
RUN apt-get autoclean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

EXPOSE 80/tcp
WORKDIR /var/www/app
CMD ["bash", "-c", "exec apache2-foreground"]
