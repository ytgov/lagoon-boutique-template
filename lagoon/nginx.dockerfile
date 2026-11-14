ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM ghcr.io/ytgov/lagoon-boutique-base-image-nginx:11.0.1

COPY --from=cli /app /app

# Define where the Drupal Root is located
ENV WEBROOT=web
