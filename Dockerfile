FROM php:8.1-alpine

WORKDIR /app

COPY . .

CMD php cron.php