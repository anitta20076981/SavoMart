<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create([
            'name' => 'UPI',
            'details' => '[
                {
                    "field": "SandBox Id",
                    "value": "5444#65@89/dsydsy"
                }
            ]',
            'status' => 'active',
            'env' => 'sandBox',
        ]);
        PaymentMethod::create([
            'name' => 'Cash On Delivery',
            'details' => '[
                {
                    "field": "Bank",
                    "value": "Bank Name"
                }
            ]',
            'status' => 'active',
            'env' => 'sandBox',
        ]);
    }
}
