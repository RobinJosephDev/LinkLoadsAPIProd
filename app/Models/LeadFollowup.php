<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $table = 'lead_follow_up';

    protected $primaryKey = 'id';  

    protected $fillable = [
        'lead_status',
        'next_follow_up_date',
        'remarks',
        'equipment',
        'products',
        'lead_no',
        'lead_date',
        'customer_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'unit_no',
        'lead_type',
        'contact_person',
        'notes',
        'contacts',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_no'); // Ensure the foreign key matches the column in the database
    }
}
