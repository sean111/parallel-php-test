FROM php:7.3-zts-alpine AS build
#Based on Sarke/php-parallel-docker
RUN apk update && \
    apk add --no-cache $PHPIZE_DEPS git

RUN wget -c https://github.com/krakjoe/parallel/archive/v0.8.1.tar.gz -O - | tar -xz

WORKDIR /parallel-0.8.1

RUN phpize

RUN ./configure

RUN make install

RUN EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
	cp "$EXTENSION_DIR/parallel.so" /parallel.so

RUN sha256sum /parallel.so

FROM php:7.3-zts-alpine

COPY --from=build /parallel.so /parallel.so

RUN EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
	mv /parallel.so "$EXTENSION_DIR/parallel.so" && \
	docker-php-ext-enable parallel
RUN mkdir -p /code
CMD ["tail", "-f", "/dev/null"]