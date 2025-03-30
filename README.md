# Laravel Logistics Backend

## 📌 About
This is the backend for a logistics management system, built with Laravel. It provides a robust API for handling shipments, customers, orders, brokers, vendors, leads, and more. The backend serves the React + TypeScript frontend.

## 🚀 Features
- **Authentication**: Secure customer and admin authentication.
- **Orders Management**: Create, update, and delete shipment orders.
- **Customers**: Store and manage customer details.
- **Shipments**: Track and manage shipments.
- **Leads and Follow-ups**: Handle business leads and follow-ups.
- **Brokers & Vendors**: Manage brokers and vendors.
- **Quotes System**: Customers can request and send quotes.
- **Dashboard**: Provides an overview of key business metrics.

## 🛠️ Installation
### Prerequisites
Ensure you have the following installed:
- [PHP](https://www.php.net/) (8.1 or later recommended)
- [Composer](https://getcomposer.org/)
- [MySQL/PostgreSQL](https://www.postgresql.org/)
- [Node.js](https://nodejs.org/) (for frontend assets, if applicable)

### Setup
1️⃣ Clone the Repository:
```sh
git clone https://github.com/your-repo/LaravelLogisticsBackend.git
cd LaravelLogisticsBackend
```
2️⃣ Install Dependencies:
```sh
composer install
npm install
```
3️⃣ Setup Environment:
```sh
cp .env.example .env
php artisan key:generate
```
Edit `.env` to configure your database settings.

4️⃣ Run Migrations:
```sh
php artisan migrate --seed
```
5️⃣ Start the Server:
```sh
php artisan serve
```
The app will be available at `http://127.0.0.1:8000/`

## 📂 Project Structure
- `app/Http/Controllers/`
  - `OrderController.php` - Manages orders (CRUD, validation, etc.)
  - `UserController.php` - Handles authentication and user management
  - `LeadFollowupController.php` - Manages lead follow-ups
  - `QuoteController.php` - Handles quotes sent by customers
  - `ShipmentController.php` - Manages shipment details
  - `CustomerController.php` - Handles customer information
  - `BrokerController.php` - Manages brokers
  - `VendorController.php` - Manages vendors
  - `AuthController.php` - Manages authentication and user sessions
  - `DashboardController.php` - Handles dashboard metrics and insights

## 🔧 API Endpoints
- **Orders**:
  - `GET /api/orders` - Get all orders
  - `POST /api/orders` - Create a new order
  - `GET /api/orders/{id}` - Get order details
  - `PUT /api/orders/{id}` - Update an order
  - `DELETE /api/orders/{id}` - Delete an order
- **Users**:
  - `POST /api/login` - User authentication
  - `POST /api/register` - User registration
- **Leads & Followups**:
  - `GET /api/leads` - Fetch all leads
  - `POST /api/leads` - Create a new lead
  - `POST /api/lead-followup` - Create a follow-up
- **Quotes**:
  - `POST /api/quotes` - Send a quote to a carrier
- **Shipments**:
  - `GET /api/shipments` - Fetch all shipments
- **Customers**:
  - `GET /api/customers` - Fetch customer data
- **Dashboard**:
  - `GET /api/dashboard` - Fetch key metrics and analytics

## ✅ Validation
Each controller ensures strict validation using Laravel's Validator. Example:
```php
$request->validate([
    'customer' => 'required|string|max:200',
    'final_price' => 'required|numeric|min:0'
]);
```

## 🛠 Testing
Run unit and feature tests:
```sh
php artisan test
```

## 📜 License
This project is licensed under the MIT License.

---
Let me know if you need any additional details! 🚀

