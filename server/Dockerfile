# This is the base image where we start from.
# This image contains the apache webserver in conjunction with PHP.
# You can more info about this image @ https://hub.docker.com/_/php
FROM php:7.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy all files in the root of this server directory.
# Files in .dockerignore are not copied
COPY . /var/www/html

# Inform docker that the container is listening on port 80.
# This is merely a hint that a certain port is useful.
# Docker won’t do anything with that information by itself.
EXPOSE 80

# The base image runs the apache daemon by default