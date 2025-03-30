<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $table = 'shipments'; // If table name is different than default (plural of model)

    // Specify which fields are mass assignable
    protected $fillable = [
        'ship_load_date',
        'ship_pickup_location',
        'ship_delivery_location',
        'ship_driver',
        'ship_weight',
        'ship_ftl_ltl',
        'ship_tarp',
        'ship_equipment',
        'ship_price',
        'ship_notes',
    ];

}
