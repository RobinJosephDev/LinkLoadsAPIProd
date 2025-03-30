<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'legal_name',
        'remit_name',
        'vendor_type',
        'service',
        'primary_address',
        'primary_city',
        'primary_state',
        'primary_country',
        'primary_postal',
        'primary_email',
        'primary_phone',
        'primary_fax',
        'scac',
        'docket_number',
        'vendor_code',
        'gst_hst_number',
        'qst_number',
        'ca_bond_number',
        'website',
        'mailing_address',
        'mailing_city',
        'mailing_state',
        'mailing_country',
        'mailing_postal',
        'mailing_email',
        'mailing_phone',
        'mailing_fax',
        'us_tax_id',
        'payroll_no',
        'wcb_no',
        'ar_name',
        'ar_email',
        'ar_contact_no',
        'ar_ext',
        'ap_name',
        'ap_email',
        'ap_contact_no',
        'ap_ext',
        'bank_name',
        'bank_phone',
        'bank_email',
        'bank_us_acc_no',
        'bank_cdn_acc_no',
        'bank_address',
        'cargo_company',
        'cargo_policy_start',
        'cargo_policy_end',
        'cargo_ins_amt',
        'liab_company',
        'liab_policy_start',
        'liab_policy_end',
        'liab_ins_amt',
        'contacts',
    ];

    protected $casts = [
        'cargo_policy_start' => 'date',
        'cargo_policy_end' => 'date',
        'liab_policy_start' => 'date',
        'liab_policy_end' => 'date',
        'contacts' => 'array', // Automatically casts JSON to an array
    ];
}
