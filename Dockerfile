FROM sarkedev/php-parallel:alpine
RUN mkdir -p /code
CMD ["tail", "-f", "/dev/null"]