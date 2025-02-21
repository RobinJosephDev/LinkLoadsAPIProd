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
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Public routes (no authentication required)
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Authenticated routes (must be logged in with Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

    // Upload route
    Route::post('/upload', [FileUploadController::class, 'uploadFile']);

    // Email route
    Route::post('/email', [EmailController::class, 'sendEmails']);

    /* Admin routes */
    Route::apiResource('/lead', LeadController::class);

    // Cached data test
    Route::get('/cached', function () {
        return response()->json(['value' => Cache::get('key', 'default value')]);
    });

    Route::apiResource('/lead-followup', LeadFollowupController::class);
    Route::apiResource('/order', OrderController::class);

    // Customer CRUD
    Route::post('/customer', [CustomerController::class, 'store']);
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);

    // User management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /* Employee routes */
    Route::get('/employee-lead', [EmployeeLeadController::class, 'index']);
    Route::get('/employee-lead/{id}', [EmployeeLeadController::class, 'show']);
    Route::put('/employee-lead/{id}', [EmployeeLeadController::class, 'update']);
    Route::delete('/employee-lead/{id}', [EmployeeLeadController::class, 'destroy']);

    // Fetch Lead Followups for logged-in employee
    Route::get('/employee-followup', [EmployeeFollowupController::class, 'index']);

    /* Carrier, Shipment, Quote, Vendor, Broker routes */
    Route::apiResource('/carrier', CarrierController::class);
    Route::apiResource('/shipment', ShipmentController::class);
    Route::apiResource('/quote', QuoteController::class);
    Route::apiResource('/vendor', VendorController::class);
    Route::apiResource('/broker', BrokerController::class);
});
