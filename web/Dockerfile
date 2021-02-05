FROM php:5-apache

RUN apt-get update && apt-get install -y sass
RUN a2enmod rewrite headers deflate

RUN install -d -o www-data /tmp/cache

COPY . /var/www/html/
RUN sass styles/main.scss styles/main.css
