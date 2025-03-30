<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_name',
        'broker_address',
        'broker_city',
        'broker_state',
        'broker_country',
        'broker_postal',
        'broker_email',
        'broker_phone',
        'broker_ext',
        'broker_fax',
    ];
}
