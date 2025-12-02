<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('customer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->string('street', 255)->nullable();
            $table->string('store_name', 255)->nullable();
            $table->string('bussiness_type', 255)->nullable();
            $table->string('product_categories')->nullable();
            $table->string('pincode', 255)->nullable();
            $table->longText('address_line1')->nullable();
            $table->longText('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country', 255)->nullable();
            $table->smallInteger('vendor_document_type')->nullable()->default('0');
            $table->string('vendor_document')->nullable();
            $table->boolean('has_gst')->default(0);
            $table->string('bussiness_name', 255)->nullable();
            $table->string('gst_number')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('gst_date_of_in_corparation')->nullable();
            $table->longText('nonGst_reason_for_exemption')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('number')->nullable();
            $table->string('signature')->nullable();
            $table->string('goods_categories')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('customer_details');
    }
};