FROM ghcr.io/ytgov/lagoon-boutique-base-image-cli:11.x

COPY composer.* /app/
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev
COPY . /app

RUN chmod 555 /app/web/sites/default && \
    chmod 444 /app/web/sites/default/settings.php && \
    chmod 444 /app/web/sites/default/services.yml
    
RUN mkdir -p -v -m775 /app/web/sites/default/files
RUN mkdir -p -v -m775 /app/private

# Define where the Drupal Root is located.
ENV WEBROOT=web
