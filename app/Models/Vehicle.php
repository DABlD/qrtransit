<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\VehicleAttribute;
use App\Models\{Vehicle, Route, Station, User};

class Vehicle extends Model
{
    use SoftDeletes, VehicleAttribute;
    
    protected $fillable = [
        'company_id', 'vehicle_id','type','passenger_limit','route_id','current_station','passenger_count', 'driver', 'conductor'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function company(){
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}