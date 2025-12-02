<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CustomerDetails extends Model
{
    use SoftDeletes;

    protected $table = 'customer_details';

    protected $fillable = ['customer_id', 'store_name', 'street','number', 'product_categories', 'postel_code', 'address_line1', 'address_line2', 'city', 'state_id', 'country_id', 'vendor_document_type', 'vendor_document', 'has_gst', 'bussiness_name', 'gst_number', 'gst_certificate', 'gst_date_of_in_corparation', 'nonGst_reason_for_exemption', 'aadhar_number', 'pan_number', 'signature', 'goods_categories', 'gst_company_name', 'company_logo', 'pickup_address_line1', 'pickup_address_line2', 'pickup_state_id', 'pickup_country_id', 'pickup_city', 'pickup_postel_code', 'pickup_phone', 'pickup_phone_code', 'bussiness_type', 'vendor_document1', 'vendor_document_type1'];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $appends = ['signature_url', 'vendor_document_url', 'gst_certificate_url', 'vendor_document_url', 'country_name', 'state_name', 'pickup_state_name', 'pickup_country_name'];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function pickUpCountry()
    {
        return $this->hasOne(Country::class, 'id', 'pickup_country_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function pickUpState()
    {
        return $this->hasOne(State::class, 'id', 'pickup_state_id');
    }

    public function getSignatureUrlAttribute()
    {
        return $this->signature ? Storage::disk('foodovity')->url($this->signature) : '';
    }

    public function getGstCertificateUrlAttribute()
    {
        return $this->gst_certificate ? Storage::disk('foodovity')->url($this->gst_certificate) : '';
    }

    public function getVendorDocumentUrlAttribute()
    {
        return $this->vendor_document ? Storage::disk('foodovity')->url($this->vendor_document) : '';
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'product_categories');
    }

    public function getCountryNameAttribute()
    {
        return $this->country_id ? $this->country->short_name : '';
    }

    public function getStateNameAttribute()
    {
        return $this->state_id ? $this->state->name : '';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getPickUpCountryNameAttribute()
    {
        return $this->pickup_country_id ? $this->pickUpCountry->short_name : '';
    }

    public function getPickUpStateNameAttribute()
    {
        return $this->pickup_state_id ? ($this->pickUpState ? $this->pickUpState->name : '') : '';
    }
}