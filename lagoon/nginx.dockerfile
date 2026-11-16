# docker-compose and Lagoon pass the built CLI image; the default only keeps
# the Dockerfile valid on its own.
ARG CLI_IMAGE=cli
FROM ${CLI_IMAGE} AS cli

FROM ghcr.io/ytgov/lagoon-boutique-base-image-nginx:11.3.0

COPY --from=cli /app /app

# Define where the Drupal Root is located
ENV WEBROOT=web
