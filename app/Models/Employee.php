<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'name', 'email', 'source_emp_id', 'image', 'user_id'
    ];

    protected $hidden = [
        'user_id',
        'id'
    ];

    protected $appends = [
        'company_id'
    ];

    public function getCompanyIdAttribute()
    {
        return $this->attributes['user_id'];
    }
}
