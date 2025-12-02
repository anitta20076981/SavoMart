<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
    use HasFactory;

    protected $table = 'customer_otps';

    protected $fillable = ['otp', 'expire_at', 'ip_address', 'phone_number'];
}
