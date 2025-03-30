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
- **File Uploads**: Secure file storage and retrieval for agreements and documents.
- **Email System**: Send emails from the system.

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
  - `AuthController.php` - Manages authentication and user sessions
  - `BrokerController.php` - Manages brokers
  - `CarrierController.php` - Manages carriers
  - `CustomerController.php` - Handles customer information
  - `DashboardController.php` - Handles dashboard metrics and insights
  - `EmailController.php` - Handles email sending
  - `EmployeeFollowupController.php` - Manages follow-ups for employees
  - `EmployeeLeadController.php` - Manages leads for employees
  - `FileTransferController.php` - Manages file uploads and downloads
  - `LeadController.php` - Handles business leads
  - `LeadFollowupController.php` - Manages lead follow-ups
  - `OrderController.php` - Manages orders (CRUD, validation, etc.)
  - `QuoteController.php` - Handles quotes sent by customers
  - `ShipmentController.php` - Manages shipment details
  - `UserController.php` - Handles authentication and user management
  - `VendorController.php` - Manages vendors

## 🔧 API Endpoints
- **Authentication**:
  - `POST /api/login` - User authentication
  - `POST /api/register` - User registration
  - `POST /api/logout` - Logout user
- **Orders**:
  - `GET /api/order` - Get all orders
  - `POST /api/order` - Create a new order
  - `GET /api/order/{id}` - Get order details
  - `PUT /api/order/{id}` - Update an order
  - `DELETE /api/order/{id}` - Delete an order
- **Users**:
  - `GET /api/user` - Get all users
  - `POST /api/user` - Create a new user
- **Leads & Followups**:
  - `GET /api/lead` - Fetch all leads
  - `POST /api/lead` - Create a new lead
  - `GET /api/lead-followup` - Fetch follow-ups
  - `POST /api/lead-followup` - Create a follow-up
- **Quotes**:
  - `POST /api/quote` - Send a quote to a carrier
- **Shipments**:
  - `GET /api/shipment` - Fetch all shipments
- **Customers**:
  - `GET /api/customer` - Fetch customer data
  - `POST /api/customer` - Create a new customer
  - `GET /api/customer/{id}` - Get customer details
  - `PUT /api/customer/{id}` - Update customer
  - `DELETE /api/customer/{id}` - Delete customer
- **Dashboard**:
  - `GET /api/dashboard-data` - Fetch key metrics and analytics
- **File Uploads**:
  - `POST /api/upload` - Upload files
  - `POST /api/carriers/{carrier}/upload` - Upload carrier agreement
  - `GET /api/download/{folder}/{filename}` - Download files
- **Email**:
  - `POST /api/email` - Send emails

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

