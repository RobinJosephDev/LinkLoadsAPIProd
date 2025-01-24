<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    protected $primaryKey = 'id';  

    protected $fillable = [
        'lead_no',
        'lead_date',
        'follow_up_date',
        'customer_name',
        'phone',
        'email',
        'website',
        'equipment_type',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'unit_no',
        'lead_type',
        'contact_person',
        'lead_status',
        'notes',
        'contacts',
        'assigned_to'
    ];
    
    public function leadFollowups()
    {
        return $this->hasMany(LeadFollowup::class, 'lead_no'); // Ensure this matches the foreign key
    }
}
