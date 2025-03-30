# Laravel Backend

## 📌 About
This is a Laravel-powered backend application that serves as the API for the logistics web portal. It provides robust validation, authentication, and data management for orders, shipments, and customer interactions.

## 🚀 Features
- **Comprehensive API**: Serves data to the frontend (React + TypeScript Admin Panel).
- **Robust Validation**: Every request is validated to ensure data integrity.
- **Authentication & Authorization**: Secure login and role-based access.
- **Order Management**: Create, update, and manage orders efficiently.
- **Database with PostgreSQL**: High-performance relational database.
- **Automated Migrations & Seeding**: Easily set up and modify database schema.

## 🛠️ Installation
### 1️⃣ Clone the Repository
```sh
git clone https://github.com/your-repo/LaravelApp.git
cd LaravelApp
```

### 2️⃣ Install Dependencies
```sh
composer install
npm install
```

### 3️⃣ Setup Environment
```sh
cp .env.example .env
php artisan key:generate
```
Configure your database settings in `.env`.

### 4️⃣ Run Migrations
```sh
php artisan migrate --seed
```

### 5️⃣ Start the Server
```sh
php artisan serve
```
The app will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## 📝 API Endpoints
### Orders
- **Get all orders**: `GET /api/orders`
- **Get a single order**: `GET /api/orders/{id}`
- **Create a new order**: `POST /api/orders`
- **Update an order**: `PUT /api/orders/{id}`
- **Delete an order**: `DELETE /api/orders/{id}`

### Validation Example (OrderController)
All fields are validated using Laravel's `Validator` class:
```php
Validator::make($request->all(), [
    'customer' => 'required|string|min:1|max:200|regex:/^[a-zA-Z0-9\s.,\'\-]+$/',
    'temperature' => 'nullable|numeric|min:-100|max:100',
    'charges.*.charge' => 'nullable|numeric|min:0|max:1000000',
    'discounts.*.charge' => 'nullable|numeric|min:0|max:1000000',
]);
```

## ✅ Testing
Run tests using PHPUnit:
```sh
php artisan test
```

## 🔗 Frontend
This backend powers the [AdminUI](https://github.com/your-repo/AdminUI) React + TypeScript application.

## 📜 License
This project is licensed under the MIT License.

