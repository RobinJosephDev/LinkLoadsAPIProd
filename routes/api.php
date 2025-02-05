<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\EmployeeLeadController;
use App\Http\Controllers\LeadFollowupController;
use App\Http\Controllers\EmployeeFollowupController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\VendorController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Public routes (no authentication required)
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Authenticated routes (must be logged in with Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);
    //Upload route
    Route::post('/upload', [FileUploadController::class, 'uploadFile']);

    //Email route
    Route::post('/email', [EmailController::class, 'sendEmails']);

    /*Admin routes */

    // CRUD operations for Leads
    Route::apiResource('/lead', LeadController::class);

    // CRUD operations for Lead Follow-ups
    Route::apiResource('/lead-followup', LeadFollowupController::class);

    // CRUD operations for Customers
    Route::post('/customer', [CustomerController::class, 'store']);
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);

    // CRUD operations for Orders
    Route::apiResource('/order', OrderController::class);

    // Routes for managing users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*Employee routes */

    // CRUD operations for Employee Leads
    Route::get('/employee-lead', [EmployeeLeadController::class, 'index']);
    Route::get('/employee-lead{id}', [EmployeeLeadController::class, 'show']);
    Route::put('/employee-lead{id}', [EmployeeLeadController::class, 'update']);
    Route::delete('/employee-lead{id}', [EmployeeLeadController::class, 'destroy']);

    // Fetch Lead Followups for logged-in employee
    Route::get('/employee-followup', [EmployeeFollowupController::class, 'index']);

    /*Carrier route */
    Route::apiResource('/carrier', CarrierController::class);

    /* Shipment route */
    Route::apiResource('/shipment', ShipmentController::class);

    /* Quote route */
    Route::apiResource('/quote', QuoteController::class);

    /*Vendor route */
    Route::apiResource('/vendor', VendorController::class);

    /*Broker route */
    Route::apiResource('/broker', BrokerController::class);

});
