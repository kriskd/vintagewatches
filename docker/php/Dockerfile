FROM php:7.2-apache

ARG APP_ENV
ARG APP_DOMAIN

RUN apt-get update && apt-get install -y \
		zip                                             `# composer` \
		unzip                                           `# composer` \
		rsync                                           `# tests` \
		libfreetype6-dev                                `# gd` \
		libpng-dev                                      `# gd` \
		libjpeg-dev                                     `# gd` \
		libc-client-dev                                 `# imap` \
		libkrb5-dev                                     `# imap` \
		libicu-dev                                      `# intl` \
	&& docker-php-ext-configure gd \
		--with-freetype-dir=/usr/include/ \
		--with-jpeg-dir=/usr/include/ \
		--with-png-dir=/usr/include/ \
	&& docker-php-ext-configure imap \
		--with-kerberos \
		--with-imap-ssl \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install -j$(nproc) \
		gd \
		pdo_mysql \
		bcmath \
		imap \
		zip \
		calendar \
		intl \
	&& apt-get purge -y \
		libfreetype6-dev \
		libpng-dev \
		libjpeg-dev \
		libc-client-dev \
		libkrb5-dev \
		libicu-dev

RUN pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug

COPY ./php-site.ini /usr/local/etc/php/conf.d/local.ini

COPY ./apache-site.conf /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's|%APP_ENV%|'$APP_ENV'|' /etc/apache2/sites-enabled/000-default.conf \
	&& sed -i 's|%APP_DOMAIN%|'$APP_DOMAIN'|' /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod env info rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
