#!/bin/bash
cd ../..
docker network create backend_with_database
docker-compose up -d
docker exec -it backend bash -c "composer install"
docker exec -it backend bash -c "php bin/console migrations:reset"
echo "Application starts on http://localhost:8080"
