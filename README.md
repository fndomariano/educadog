# EducaDog Web application

This project consists in an application to register information about customer and their pets.

## Install

a) Configure `.env` file

```bash 
$ cp .env.example .env
```

b) Up the Docker containers
```bash 
$ docker-compose -f docker-compose-dev.yml up -d
```
c) Enter on container
```bash
$ docker exec -it carolnabel-app bash
```

d) Install dependencies
```bash
root@docker_php:/app$ composer install
```

e) Run migrations
```bash
root@docker_php:/app$ php artisan migrate
```

f) You can populate the database with Seeders
```bash
root@docker_php:/app$ php artisan db:seed
```

g) Access 
```
http://localhost:8000
```
