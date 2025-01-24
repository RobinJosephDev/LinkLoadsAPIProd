<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carriers';
    protected $primaryKey = 'id';

    // List of attributes that are mass assignable
    protected $fillable = [
        'dba',
        'legal_name',
        'remit_name',
        'acc_no',
        'branch',
        'website',
        'fed_id_no',
        'pref_curr',
        'pay_terms',
        'form_1099',
        'advertise',
        'advertise_email',
        'carr_type',
        'rating',
        'brok_carr_aggmt',
        'docket_no',
        'dot_number',
        'wcb_no',
        'ca_bond_no',
        'us_bond_no',
        'scac',
        'csa_approved',
        'hazmat',
        'smsc_code',
        'approved',
        'li_provider',
        'li_policy_no',
        'li_coverage',
        'li_start_date',
        'li_end_date',
        'ci_provider',
        'ci_policy_no',
        'ci_coverage',
        'ci_start_date',
        'ci_end_date',
        'coi_cert',
        'primary_address',
        'primary_city',
        'primary_state',
        'primary_country',
        'primary_postal',
        'primary_phone',
        'mailing_address',
        'mailing_city',
        'mailing_state',
        'mailing_country',
        'mailing_postal',
        'mailing_phone',
        'int_notes',
        'contact',
        'equipment',
        'lane',
    ];

    // Casting fields as arrays or other types
    protected $casts = [
        'contact' => 'array',
        'equipment' => 'array',
        'lane' => 'array',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    
    

}
