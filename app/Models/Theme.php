<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        "name", "value"
        ,'company_id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
