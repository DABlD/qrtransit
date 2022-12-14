<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\DeviceAttribute;
use App\Models\{Device, Route, Station, Ad, User};

class Device extends Model
{
    use SoftDeletes, DeviceAttribute;
    
    protected $fillable = [
        'company_id', 'station_id','route_id','device_id','status','description'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function route(){
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function station(){
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    public function ad(){
        return $this->belongsTo(Ad::class, 'ad_id', 'id');
    }

    public function company(){
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}