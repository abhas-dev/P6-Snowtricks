## Run Locally

Clone the project

```bash
  git@github.com:Redpanda2/P6-Snowtricks.git
```

Run the docker-compose

```bash
  docker-compose up -d --build
  OR
  docker-compose build
  docker-compose up -d

```

Log into the PHP container

```bash
  docker exec -it www_docker_sf bash
```

### Install the project
```
Composer install
```

Configure database connexion(no password required)
```yaml
  DATABASE_URL="mysql://root:@127.0.0.1:3306/db_name?serverVersion=5.7"
```

Configure mailer dsn in .env
```yaml
  ###> symfony/mailer ###
  MAILER_DSN=smtp://mailhog_docker_sf:1025
  MAILER_SENDER_EMAIL=contact@snowtricks.com
  MAILER_SENDER_NAME="Snowtricks"
```

Create database and install it (in www container)
```yaml
  symfony console doctrine:database:create
  symfony console doctrine:migrations:migrate
```

Load Fixtures
```yaml
  symfony console doctrine:fixtures:load --no-interaction
```


*Application is available at http://127.0.0.1:8741

*WebUi for database (phpmyadmin) is available at http://127.0.0.1:8080

*WebUi for mail catcher (mailhog) is available at http://127.0.0.1:8025


## Ready to use with

This docker-compose provides you :

- PHP:8.0.12-apache-bullseye
    - Composer
    - Symfony CLI
    - and some other php extentions
    - nodejs, npm, yarn
- mysql 5.7
- phpmyadmin
- mailhog


## Requirements

- Docker
- Docker-compose