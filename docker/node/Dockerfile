FROM node:9

WORKDIR /var/www/symfony

RUN npm i npm@latest -g

EXPOSE  8080
CMD npm install && npm i -g gulp && npm run build:symfony