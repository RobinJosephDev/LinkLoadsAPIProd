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
use App\Http\Controllers\FileTransferController;
use App\Http\Controllers\EmployeeLeadController;
use App\Http\Controllers\LeadFollowupController;
use App\Http\Controllers\EmployeeFollowupController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Public routes (no authentication required)
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Authenticated routes (must be logged in with Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Upload
    Route::post('/upload', [FileTransferController::class, 'uploadFile']);
    Route::post('/carriers/{carrier}/upload', [CarrierController::class, 'uploadAgreement']);

    //Download
    Route::get('/download/{folder}/{filename}', [FileTransferController::class, 'downloadFile']);

    // Email
    Route::post('/email', [EmailController::class, 'sendEmails']);

    /* Admin */

    //Dashboard
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

    //Leads    
    Route::apiResource('/lead', LeadController::class);

    //Lead Follow-up
    Route::apiResource('/lead-followup', LeadFollowupController::class);

    // Customers
    Route::post('/customer', [CustomerController::class, 'store']);
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);

    //Orders
    Route::apiResource('/order', OrderController::class);
    Route::post('/order/{id}/duplicate', [OrderController::class, 'duplicate']);

    // Users
    Route::apiResource('/user', UserController::class);

    //Quotes
    Route::apiResource('/quote', QuoteController::class);
    Route::apiResource('/shipment', ShipmentController::class);

    //Carriers&Co
    Route::apiResource('/carrier', CarrierController::class);
    Route::apiResource('/vendor', VendorController::class);
    Route::apiResource('/broker', BrokerController::class);

    // Cached data test
    Route::get('/cached', function () {
        return response()->json(['value' => Cache::get('key', 'default value')]);
    });

    //Dispatches
    Route::apiResource('/dispatch', DispatchController::class);

    //Companies
    Route::apiResource('/company', CompanyController::class);

    //Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /* Employee */

    //Leads
    Route::apiResource('/employee-lead', EmployeeLeadController::class);

    //Follow-ups
    Route::get('/employee-followup', [EmployeeFollowupController::class, 'index']);
});
