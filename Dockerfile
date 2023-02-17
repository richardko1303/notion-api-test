# Use the official PHP image as the base image
FROM php:7.4-apache

# Copy the application files into the container
COPY . /

# Set the working directory in the container
WORKDIR .

# Install necessary PHP extensions
RUN php artisan october:install

# Expose port 80
EXPOSE 80

# Define the entry point for the container
CMD ["apache2-foreground"]
