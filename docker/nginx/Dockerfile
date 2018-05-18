FROM alpine:3.6

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/
ADD symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf
RUN mkdir /etc/nginx/sites-enabled
RUN mkdir /etc/nginx/sites-available
RUN cp /etc/nginx/conf.d/symfony.conf /etc/nginx/sites-available/symfony.conf
RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony.conf
RUN rm /etc/nginx/conf.d/default.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
