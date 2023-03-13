# Application for a post about running Nginx + PHP + MongoDB with Docker-Compose.

The repository contains files for preparing the environment and running a simple PHP script. 

The application runs with Nginx + PHP-fpm 8.2 + MongoDB 6, the environment is run with Docker compose. It also uses Composer to include the required MongoDB packages. 

The application gets access data from environment variables, connects to the database and writes 1000 documents to the database. 

That's it! However, it's a good starting point to do something more.

## Installation

1. Clone the repository and navigate to the project folder.

2. Run Image build from Dockerfile

```
docker build -t php8.2-fpm-mongo .
```

3. Run docker-compose

```
docker-compose up -d
```

4. Connect to a container with PHP-fpm

```
docker exec -it [php-fpm-container-name] bash
```

5. Install the Composer packages needed for the application (composer.json)

```
composer install
```

6. Open localhost in your browser

7. Enjoy modifying the index.php script and checking the result by simply reloading the localhost page in your browser

8. Stop docker-compose after experimenting

```
docker-compose down
```
