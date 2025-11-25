Agro Predictor (basic scaffold)

This repository contains a minimal scaffold for an agricultural yield prediction microservice stack.

Services (via Docker Compose):
- app: PHP-FPM (basic PHP backend)
- web: Nginx
- db: MySQL 8
- redis: Redis
- ml: Python Flask microservice (mock predictor)

Quick start (from this folder):

```powershell
docker-compose up --build
```

- PHP app will be served by Nginx on http://localhost:8080
- ML service listens on container port 5000 (mapped to host 5001)

Replace `documentacion_base.pdf` in the project root with the original PDF if available.
