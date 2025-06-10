<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = [
        'carrier',
        'contact',
        'equipment',
        'driver_mobile',
        'truck_unit_no',
        'trailer_unit_no',
        'paps_pars_no',
        'tracking_code',
        'border',
        'currency',
        'rate',
        'charges',
        'discounts',
        'gst',
        'pst',
        'hst',
        'qst',
        'final_price',
    ];

    protected $casts = [
        'charges' => 'array',
        'discounts' => 'array',
        'rate' => 'decimal:2',
        'gst' => 'decimal:2',
        'pst' => 'decimal:2',
        'hst' => 'decimal:2',
        'qst' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];
}
