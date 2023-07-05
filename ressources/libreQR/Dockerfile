FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y unzip libpng-dev 
RUN docker-php-ext-install gd
ADD main.zip /main.zip
WORKDIR /var/www/html
RUN unzip /main.zip
RUN mv libreqr/* . 

ADD config.inc.php /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
ENV LIBREQR_THEME=libreqr \
    LIBREQR_DEFAULT_LOCALE=fr \
    LIBREQR_CUSTOM_TEXT_ENABLED=true \
    LIBREQR_CUSTOM_TEXT="LibreQR on docker" \
    LIBREQR_DEFAULT_REDUNDANCY=high \
    LIBREQR_DEFAULT_MARGIN=20 \
    LIBREQR_DEFAULT_SIZE=300 \
    LIBREQR_DEFAULT_BGCOLOR=FFFFFF \
    LIBREQR_DEFAULT_FGCOLOR=000000