# Use the official PHP image as the base image
FROM php:7.4-apache

# Copy the application files into the container
COPY . /

# Set the working directory in the container
WORKDIR /

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    intl \
    zip \
    && a2enmod rewrite

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer october:install

# Expose port 80
EXPOSE 80

# Define the entry point for the container
CMD ["apache2-foreground"]
