# carolnabel web app

This project consists in an application to register information about owners and their pets.

## Install

a) Configure `.env` file

```bash 
$ cp .env.example .env
```

b) Up the Docker containers
```bash 
$ docker-compose -f docker-compose-dev.yml up -d
```

c) Install dependencies
```bash
$ docker-compose exec php /bin/bash
root@docker_php:/app$ composer install
```

d) Run migrations
```bash
$ docker-compose php php artisan migration
```

e) You can populate the database with Seeders
```bash
$ docker-compose php php artisan db:seed
```

f) Access 
```
http://localhost
```
