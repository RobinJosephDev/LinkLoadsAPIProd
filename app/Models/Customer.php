<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cust_name',
        'cust_type',
        'cust_ref_no',
        'cust_website',
        'cust_email',
        'cust_contact_no',
        'cust_contact_no_ext',
        'cust_tax_id',
        'cust_primary_address',
        'cust_primary_city',
        'cust_primary_state',
        'cust_primary_country',
        'cust_primary_postal',
        'cust_primary_unit_no',
        'cust_mailing_address',
        'cust_mailing_city',
        'cust_mailing_state',
        'cust_mailing_country',
        'cust_mailing_postal',
        'cust_mailing_unit_no',
        'cust_ap_name',
        'cust_ap_address',
        'cust_ap_city',
        'cust_ap_state',
        'cust_ap_country',
        'cust_ap_postal',
        'cust_ap_unit_no',
        'cust_ap_email',
        'cust_ap_phone',
        'cust_ap_phone_ext',
        'cust_ap_fax',
        'cust_broker_name',
        'cust_bkp_notes',
        'cust_bkspl_notes',
        'cust_credit_status',
        'cust_credit_mop',
        'cust_credit_appd',
        'cust_credit_expd',
        'cust_credit_terms',
        'cust_credit_limit',
        'cust_credit_notes',
        'cust_credit_application',
        'cust_credit_currency',
        'cust_sbk_agreement',
        'cust_credit_agreement',
        'cust_contact',
        'cust_equipment',
    ];



}
