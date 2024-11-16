# CRM project

## Setup local
- Open terminal run build and start docker : **docker-compose up -d**
- Copy and rename file **.env.example** into **.env**
    + Replace line SESSION_DRIVER=database into SESSION_DRIVER=file in .env
- Open docker container run : **docker exec -it laravel_erp bash**
- In docker container run command:
    - ```composer install```
    - ```php artisan key:generate```

## Check convension
./vendor/bin/phpcs -sq --no-colors --report-full=report-full.log --standard=phpcs.xml

## Fix convension
./vendor/bin/phpcbf -sq --standard=phpcs.xml

## Set Permission to write Log
chmod -R 777 storage/logs/

## Installation Docker to build Project
### Build development environment
1. Clone the repository
2. Navigate to the project directory
```cd [project-name]```
3. Copy file .env from .env-sample
```cp .env-sample .env```

4. Build the Docker Images: <br>

Manual ```docker-compose build```

5. Start the containers: <br>

Manual ```docker-compose up -d```

6. Intall composer: <br>

Manual ```docker-compose exec laravel-erp composer install```

7. Generate key:<br>

Manual ```docker-compose exec laravel-erp php artisan key:generate```

8. In the first time build source code, you run migrate database and create masterdata: <br>

Manual
```docker-compose exec laravel-erp php artisan migrate```
```docker-compose exec laravel-erp php artisan import-data```

9. Access the application:<br>
Open http://localhost:8000

10. To stop Docker

Manual ```docker-compose down```

11. Run migrate database

Manual ```docker-compose exec laravel-erp php artisan migrate```

