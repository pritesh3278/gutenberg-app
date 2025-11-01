# Gutenberg Books App

A Laravel application for browsing Gutenberg books.

## Prerequisites

- PHP 8.1+
- Composer
- PostgreSQL
- Node.js & NPM

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/gutenberg-app.git
   cd gutenberg-app

Install PHP dependencies

bash
composer install
Install JavaScript dependencies

bash
npm install
Environment Setup

bash
cp .env.example .env
php artisan key:generate
Configure Database

Create a PostgreSQL database named gutenberg_books

Update .env with your database credentials:

env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gutenberg_books
DB_USERNAME=postgres
DB_PASSWORD=yourpassword
Run Migrations

bash
php artisan migrate
Build Frontend Assets

bash
npm run build
Start Development Server

bash
php artisan serve
Access the Application
Open http://localhost:8000 in your browser

Database Seeding (Optional)
If you have seed data:

bash
php artisan db:seed
API Endpoints
GET /api/categories - Get all book categories

GET /api/books - Get books with filters

GET /api/books/{id} - Get specific book details

text

## Step 4: For Others to Run Your Project

### 4.1 Clone and Setup
```bash
# Clone the repository
git clone https://github.com/yourusername/gutenberg-app.git
cd gutenberg-app

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Update .env with your database credentials
4.2 Database Configuration
Edit .env file:

env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gutenberg_books
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
4.3 Run the Application
bash
# Run migrations
php artisan migrate

# Build frontend assets
npm run build

# Start development server
php artisan serve