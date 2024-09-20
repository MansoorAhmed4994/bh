<?php

namespace App\Models\Client;


use App\Models\Orderpayments;
use App\Models\User;
use App\Models\Country_codes;
use App\Models\ActivityLogs;
// use App\Models\Client\Cities;
use Illuminate\Database\Eloquent\Model;

class ManualOrders extends Model
{
    protected $guarded = [];  
    public function customers()
    {
        return $this->belongsTo(Customers::class,'customers_id','id');
    }
    
    public function users()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    
    public function UsersUpdatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    
    public function AssignTo()
    {
        return $this->belongsTo(User::class,'assign_to','id');
    }
    
    public function activity_logs()
    {
        return $this->hasMany(ActivityLogs::class,'ref_id','id');
    }
    
    public function CustomerOrderSummary()
    {
        return $this->hasMany(ActivityLogs::class,'ref_id','id');
    }
    
    
    public function riders()
    {
        return $this->belongsToOne(Riders::class);
    }
    
    public function cities()
    {
        // return $this->belongsTo(Cities::class,'city','id');
        return $this->belongsTo(Cities::class,'cities_id','id'); 
        // return $this->hasMany(Cities::class,'cities_id','id'); 
        
    }
    
    
    
    public function orderpayments()
    {
        return $this->hasMany(Orderpayments::class,'order_id','id');
    }
    
    public function country_codes()
    {
        return $this->belongsTo(Country_codes::class,'country_code_','Country_code_id');
    }
    //
}
