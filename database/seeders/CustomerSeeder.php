<?php

namespace Database\Seeders;

use App\Models\CustomerAccount;
use App\Models\CustomerDetails;
use App\Models\CustomerSubscription;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = \App\Models\Customer::factory(10)->create();

        foreach ($customers as $customer) {
            CustomerAccount::create([
                'customer_id' => $customer['id'],
            ]);
            CustomerDetails::create([
                'customer_id' => $customer['id'],
                'country' => 105,
                'state' => 17,
            ]);
            CustomerSubscription::create([
                'customer_id' => $customer['id'],
                'subscription_plan_id' => 1,
                'from' => date('Y-m-d'),
                'to' => date('Y-m-d', strtotime('+1 year')),
                'orders_processed' => 0,
                'products_featured' => 0,
                'rfq_processed' => 0,
                'rfq_submitted' => 0,
                'payment_method_id' => 0,
                'payment_reference' => null,
                'payment_status' => 'pending',
                'status' => 'active',
            ]);
        }
    }
}
