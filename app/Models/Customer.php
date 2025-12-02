<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;


    protected $table = 'customers';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['first_name', 'last_name', 'approve', 'email', 'country_code_id', 'country_code', 'phone', 'password', 'phone_verified_at', 'status', 'is_vendor', 'profile_picture','customer_details_id','profile_picture'];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['phone_number', 'name', 'profile_picture_url'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function countryCode()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_code_id');
    }

    public function getPhoneNumberAttribute()
    {
        return $this->country_code ? '+' . $this->country_code . $this->phone : $this->phone;
    }

    public function quoteRequests()
    {
        return $this->hasMany(QuoteRequest::class, 'customer_id', 'id');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class, 'customer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    // public function customerAccount()
    // {
    //     return $this->hasOne(CustomerAccount::class, 'customer_id');
    // }

    public function customerDetails()
    {
        return $this->hasOne(CustomerDetails::class, 'customer_id');
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture  ? Storage::disk('grocery')->url($this->profile_picture) : '';
    }
}
