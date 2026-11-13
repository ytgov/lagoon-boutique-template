ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM ghcr.io/ytgov/lagoon-boutique-base-image-fpm:11.x

COPY --from=cli /app /app
