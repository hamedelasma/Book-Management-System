# Bookstore Management System

## Project Overview

A Laravel-based web application for managing a collection of books with features like listing, adding, updating,
deleting, and searching books, along with user authentication and role-based access control.

## Features

1. List Books
2. Add Books (Admin only)
3. Update Book Details (Admin only)
4. Delete Books (Admin only)
5. Add Authors (Admin only)
6. Update Author Details (Admin only)
7. Delete Authors (Admin only)
8. List Authors
9. Search Books using Meilisearch
10. User Authentication with Admin and User roles
11. RESTful APIs for book operations

## Technology Stack

- **Backend**: Laravel 11.28
- **Database**: MySQL
- **Search Engine**: Meilisearch

## Prerequisites

- PHP >= 8.3
- Composer
- MySQL
- Git

## Setting Up the Project

1. **Clone the repository**
   ```sh
   git clone https://github.com/hamedelasma/bookstore-management-system.git
   cd bookstore-management-system
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Copy the .env.example file to .env and configure your database settings
   ```
   cp .env.example .env
   ```

4. Generate an application key

   ```
   php artisan key:generate
   ```

5. Run database migrations and seeders
   ```
   php artisan migrate --seed
   ```

6. Install and configure Meilisearch
   ```
   # Install Meilisearch (adjust based on your OS)
   curl -L https://install.meilisearch.com | sh

   # Run Meilisearch
   ./meilisearch
   ```

7. Index the books in Meilisearch
   ```
   php artisan scout:import "App\Models\Book"
   ```

8. Start the development server
   ```
   php artisan serve
   ```

Your Bookstore Management System should now be running at `http://localhost:8000`.

## API Documentation

The API documentation for this system is available at `https://documenter.getpostman.com/view/13965867/2sAXxV7AXL`.

## Running Tests

To run the automated tests for this system, use the following command:

```
php artisan test
```


## Production URL

```
https://books.ribal.ly
```

## Users Credentials

- Admin
  - Email:admin@example.com
  - Password:password
- User
  - Email:user@example.com
  - Password:password


