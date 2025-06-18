<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'invoice_terms',
        'rate_conf_terms',
        'quote_terms',
        'invoice_reminder',
        'address',
        'city',
        'state',
        'country',
        'postal',
        'email',
        'phone',
        'cell',
        'fax',
        'invoice_prefix',
        'SCAC',
        'docket_no',
        'carrier_code',
        'gst_hst_no',
        'qst_no',
        'ca_bond_no',
        'website',
        'obsolete',
        'us_tax_id',
        'payroll_no',
        'wcb_no',
        'dispatch_email',
        'ap_email',
        'ar_email',
        'cust_comm_email',
        'quot_email',
        'bank_info',
        'cargo_insurance',
        'liablility_insurance',
        'company_package',
        'insurance'
    ];

    protected $casts = [
        'obsolete' => 'boolean',
        'bank_info' => 'array',
        'cargo_insurance' => 'array',
        'liablility_insurance' => 'array',
    ];
}
