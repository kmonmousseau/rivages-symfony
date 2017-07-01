FROM debian:jessie

ARG APT_MIRROR=cdn-fastly.deb.debian.org

ENV DEBIAN_FRONTEND=noninteractive

RUN echo "export TERM=xterm" >> /etc/bash.bashrc

RUN apt-get clean && apt-get update && apt install -y wget curl

RUN echo "deb http://packages.dotdeb.org jessie all" > /etc/apt/sources.list.d/dotdeb.list
RUN wget -O- https://www.dotdeb.org/dotdeb.gpg | apt-key add -

# Install packages (multiple apt-get install due to debian http-redir error not yet fixed)
RUN apt-get clean && apt-get update && apt install -y \
    mysql-client \
    php7.0
RUN apt-get install -y \
    libapache2-mod-php7.0 \
    php7.0-mysql \
    php7.0-curl \
    php7.0-json \
    php7.0-gd \
    php7.0-mcrypt \
    php7.0-msgpack \
    php7.0-memcached \
    php7.0-intl \
    php7.0-sqlite3 \
    php7.0-gmp \
    php7.0-geoip \
    php7.0-mbstring \
    php7.0-xml \
    php7.0-zip \
    php7.0-imagick
RUN apt-get install -y \
    php7.0-xdebug

# Php config (fix .so directories)
RUN sed -i 's/memory_limit = .*/memory_limit = '512M'/' /etc/php/7.0/apache2/php.ini && \
    sed -i 's/memory_limit = .*/memory_limit = '512M'/' /etc/php/7.0/cli/php.ini && \
    sed -i "s/^;date.timezone =$/date.timezone = \"Europe\/Paris\"/" /etc/php/7.0/cli/php.ini &&\
    sed -i "s/^;date.timezone =$/date.timezone = \"Europe\/Paris\"/" /etc/php/7.0/apache2/php.ini

# Configure xDebug extension
RUN sed -i '1 a xdebug.remote_autostart=true' /etc/php/7.0/mods-available/xdebug.ini && \
    sed -i '1 a xdebug.remote_connect_back=1 ' /etc/php/7.0/mods-available/xdebug.ini && \
    sed -i '1 a xdebug.remote_enable=1' /etc/php/7.0/mods-available/xdebug.ini && \
    sed -i '1 a xdebug.max_nesting_level=1200' /etc/php/7.0/mods-available/xdebug.ini && \
    sed -i '1 a xdebug.idekey=PHPSTORM' /etc/php/7.0/mods-available/xdebug.ini

# Configure default site (apache)
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ADD vhost /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite ssl vhost_alias && \
    mkdir /var/www/html/web && \
    /etc/init.d/apache2 restart

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 22 80

# Clean docker image
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html

ADD start.sh /start.sh
RUN chmod +x /start.sh

CMD /start.sh