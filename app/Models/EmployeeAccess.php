<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAccess extends Model
{
    protected $table = 'employee_access';

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
