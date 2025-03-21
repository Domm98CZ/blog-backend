#!/bin/bash
cd ../..
docker network create backend_with_database
docker-compose up -d
echo "Application starts on http://localhost:8080"
