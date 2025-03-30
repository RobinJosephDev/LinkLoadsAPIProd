<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quotes';
    protected $primaryKey = 'id';
    // Define the fillable properties
    protected $fillable = [
        'quote_type',
        'quote_customer',
        'quote_cust_ref_no',
        'quote_booked_by',
        'quote_temperature',
        'quote_hot',
        'quote_team',
        'quote_air_ride',
        'quote_tarp',
        'quote_hazmat',
        'quote_pickup',
        'quote_delivery',
    ];

    // Specify that quote_pickup and quote_delivery are JSON fields
    protected $casts = [
        'quote_pickup' => 'array',
        'quote_delivery' => 'array',
    ];
}
