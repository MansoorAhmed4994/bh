<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Page extends Model
{
    use Notifiable;
    public $timestamps = false;
    public function page_permission()
    {
        return $this->belongsToMany(Page::class);
    }
    //
}
