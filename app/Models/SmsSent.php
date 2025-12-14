<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSent extends Model
{
    protected $fillable = ['user_id', 'phone_number', 'message', 'code', 'status', 'sent_at', 'verified_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
