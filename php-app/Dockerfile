FROM php:8-cli

# Install MySQL extension and SQLite (for optional file-based)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create app directory
WORKDIR /var/www/html

# Copy application
COPY . /var/www/html/

# Create uploads directory
RUN mkdir -p /var/www/html/uploads && chmod 777 /var/www/html/uploads

# Expose port (PHP built-in server)
EXPOSE 8470

# Run PHP built-in server (simple, no Apache needed)
CMD ["php", "-S", "0.0.0.0:8470", "-t", "/var/www/html"]
