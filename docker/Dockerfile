FROM php:5.6-apache
 
RUN apt-get update -yqq \
  && apt-get install -yqq --no-install-recommends \
    git \
    zip \
    unzip \
  && rm -rf /var/lib/apt/lists

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Enable PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Add cake and composer command to system path
ENV PATH="${PATH}:/var/www/html/lib/Cake/Console"
ENV PATH="${PATH}:/var/www/html/app/Vendor/bin"
 
# COPY apache site.conf file
COPY ./docker/apache/site.conf /etc/apache2/sites-available/000-default.conf
 
RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/fqdn.conf
RUN a2enconf fqdn

# Copy the source code into /var/www/html/ inside the image
COPY . .
 
# Set default working directory
WORKDIR /var/www/html/app

# Update composer
RUN composer update

# tmpの作成
RUN mkdir -p \
    tmp/cache/models \
    tmp/cache/persistent \
  && chown -R :www-data \
    tmp \
  && chmod -R 770 \
    tmp

# Apacheの再起動
RUN a2enmod rewrite \
  && service apache2 restart

 
EXPOSE 80