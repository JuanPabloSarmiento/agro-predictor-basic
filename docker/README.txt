This folder contains helper notes for Docker usage.

Run from project root:

    docker-compose up --build

Services:
- app: PHP-FPM backend
- web: Nginx (exposes 8080)
- db: MySQL 8
- redis: Redis
- ml: Flask mock predictor (mapped to host 5001)

Replace `documentacion_base.pdf` with the original PDF if available.
