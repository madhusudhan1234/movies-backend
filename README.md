# Movie Application

A Laravel-based RESTful API for movie management.

## Requirements

- **Docker** & **Docker Compose**
- **Git**

## Tech Stack

- **PHP 8.5** (Alpine-based)
- **Laravel 12**
- **PostgreSQL**
- **Redis**
- **Nginx**

## Local Development Setup

### 1. Clone the Repository

```bash
git clone git@github.com:madhusudhan1234/movie-backend.git
cd movie-backend
```

### 2. Configure Environment

Copy the example environment file and update as needed:

```bash
cp .env.example .env
```

Update the following variables in `.env` for Docker:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=movie_app
DB_USERNAME=root
DB_PASSWORD=password

REDIS_HOST=redis

# Optional: Set your UID/GID for proper file permissions
UID=1000
GID=1000

# CORS configuration for frontend
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

### 3. Start Docker Containers

```bash
docker compose up -d --build
```

This will start the following services:

| Service    | Container  | Port | Description         |
|------------|------------|------|---------------------|
| `app`      | app        | -    | PHP-FPM application |
| `nginx`    | nginx      | 80   | Web server          |
| `postgres` | postgres   | 5432 | PostgreSQL database |
| `redis`    | redis      | 6379 | Redis cache         |

### 4. Install Dependencies

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

### 5. Run Database Migrations

```bash
docker compose exec app php artisan migrate
```

### 6. (Optional) Seed the Database

```bash
docker compose exec app php artisan db:seed
```

### 7. (Optional) Link storage (if you use storage directory for file upload)

```shell
docker compose exec app php artisan storage:link 
```

### 8. Start horizon

```shell
docker compose exec app php artisan horizon
```

## Accessing the API

- **Base URL**: http://localhost/api

## Useful Commands

```bash
# View logs
docker compose logs -f

# Stop containers
docker compose down

# Rebuild containers
docker compose up -d --build

# Run artisan commands
docker compose exec app php artisan <command>

# Run tests
docker compose exec app php artisan test

# Clear caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

## API Endpoints

### Authentication

| Method | Endpoint          | Auth | Description              |
|--------|-------------------|------|--------------------------|
| POST   | /api/register     | No   | Register a new user      |
| POST   | /api/login        | No   | Login and get token      |
| POST   | /api/logout       | Yes  | Logout (revoke token)    |

### User

| Method | Endpoint   | Auth | Description               |
|--------|------------|------|---------------------------|
| GET    | /api/user  | Yes  | Get authenticated user    |

### Password Reset

| Method | Endpoint             | Auth | Description              |
|--------|----------------------|------|--------------------------|
| POST   | /api/forgot-password | No   | Send password reset link |
| POST   | /api/reset-password  | No   | Reset password with token|

### Email Verification

| Method | Endpoint                      | Auth | Description                 |
|--------|-------------------------------|------|-----------------------------|
| GET    | /api/email/verify/{id}/{hash} | No   | Verify email address        |
| POST   | /api/email/resend             | Yes  | Resend verification email   |

### Movies

| Method | Endpoint           | Auth | Description          |
|--------|--------------------|------|----------------------|
| GET    | /api/movies        | No   | List all movies      |
| GET    | /api/movies/{id}   | No   | Get a specific movie |
| POST   | /api/movies        | Yes  | Create a new movie   |
| PUT    | /api/movies/{id}   | Yes  | Update a movie       |
| DELETE | /api/movies/{id}   | Yes  | Delete a movie       |

### Favorites

| Method | Endpoint                    | Auth | Description              |
|--------|-----------------------------|------|--------------------------|
| POST   | /api/movies/{id}/favorite   | Yes  | Add movie to favorites   |
| DELETE | /api/movies/{id}/favorite   | Yes  | Remove from favorites    |

Following is the collection for Postman:

[Download Postman Collection](public/postman/Movie-Backend.postman_collection.json)

## CORS Configuration

CORS is configured via the `CORS_ALLOWED_ORIGINS` environment variable:

```env
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

Likewise Frontend Url is there for the email reset password link:
```env
FRONTEND_URL=http://localhost:5173
```
