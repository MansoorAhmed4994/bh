<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLogs extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    //
    
    public function users()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
