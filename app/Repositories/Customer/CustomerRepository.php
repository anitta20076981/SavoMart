<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use App\Models\CustomerDetails;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getForDatatable($data)
    {
        $customer = Customer::select(['*'])
            ->orderBy('created_at', 'desc')
            ->where(function (Builder $query) use ($data) {
                if ($data['status'] != '') {
                    $query->where('status', '=', $data['status']);
                }
            });

        return $customer;
    }

    public function createCustomer($details)
    {
        $customer = new Customer();

        foreach ($details as $key => $value) {
            if (!empty($value)) {
                $customer->$key = $value;
            }
        }
        $customer->save();

        return $customer;
    }

    public function updateCustomer($customerId, $details)
    {
        $customer = Customer::find($customerId);

        foreach ($details as $key => $value) {
            $customer->$key = $value;
        }
        $customer->save();

        return $customer;
    }

    public function getCustomer($customerId)
    {
        return Customer::find($customerId);
    }

    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function deleteCustomer($customerId)
    {
        return Customer::find($customerId)->delete();
    }

    public function createRole($data, $permissions)
    {
        $role = Role::create($data);
        $role->syncPermissions($permissions);

        return $role;
    }

    public function save($input)
    {
        if ($customer = Customer::create($input)) {
            return $customer;
        }

        return false;
    }

    public function updateRole($roleId, $data, $permissions)
    {
        $role = Role::find($roleId);
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($permissions);

        return $role;
    }

    public function getRole($roleId)
    {
        return Role::find($roleId);
    }

    public function getAllRoles($data)
    {
        return Role::select(app(Role::class)->getTable() . '.*')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status'])) {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function getAllRole()
    {
        return Role::all();
    }

    public function deleteRole($roleId)
    {
        return Role::find($roleId)->delete();
    }

    public function customerStatusUpdate($customerId)
    {
        $customer = Customer::find($customerId);
        $customer->status = $customer->status ? 0 : 1;
        $customer->save();

        return $customer;
    }

    public function roleStatusUpdate($roleId)
    {
        $role = Role::find($roleId);
        $role->status = $role->status ? 0 : 1;
        $role->save();

        return $role;
    }

    public function roleCustomers($id)
    {
        $role = Role::find($id);

        return $role->customers;
    }

    public function getPermissionTemplate()
    {
        return [
            ['label' => 'Attribute Sets', 'key' => 'attribute_set'],
            ['label' => 'Banner', 'key' => 'banner'],
            ['label' => 'Brand', 'key' => 'brand'],
            ['label' => 'Catalog Rule', 'key' => 'catalog_rule'],
            ['label' => 'Category', 'key' => 'categories'],
            ['label' => 'Pages', 'key' => 'pages'],
            ['label' => 'Payment Method', 'key' => 'payment_method'],
            ['label' => 'Quote', 'key' => 'quote'],
            ['label' => 'Quote Request', 'key' => 'quote_request'],
            ['label' => 'Roles and permission', 'key' => 'role'],
            ['label' => 'Settings', 'key' => 'settings'],
            ['label' => 'Products', 'key' => 'products'],
            ['label' => 'Product Attributes', 'key' => 'attribute'],
            ['label' => 'Shipment Method', 'key' => 'shipment_method'],
            ['label' => 'Tax', 'key' => 'tax'],
            ['label' => 'Customers', 'key' => 'customer'],

        ];
    }

    public function searchRole($keyword)
    {
        $role = Role::where('status', 1)->where('name', 'like', "%{$keyword}%");

        if (auth()->customer()->roles_array) {
            $role = $role->whereIn('id', auth()->customer()->roles_array);
        }

        return $role->orderBy('name', 'asc')->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getAllCustomersexcepetSuperAdmin($data)
    {
        $customers = Customer::where('email', '!=', 'web@gmail.com')->select(app(Customer::class)->getTable() . '.*');

        if (isset($data['status']) && $data['status'] != '') {
            $customers->where('status', $data['status']);
        }
        if (isset($data['role_id']) && $data['role_id'] != "") {
            $customers->whereHas('roles', function ($query) use ($data) {
                return $query->where('role_id', $data['role_id']);
            });
        }

        return $customers;
    }

    public function deleteTokens(array $tokens = [])
    {
        return Customer::whereIn('fcm_token', $tokens)->update(['fcm_token' => '']);
    }

    public function registerDevice(array $input)
    {
        $fcmId = implode('-:-', [$input['device_type'], $input['fcm_token']]);
        $customer = Customer::find(auth()->customer()->id);
        $customer->fcm_token = $fcmId;

        if ($customer->save()) {
            return $customer;
        }

        return false;
    }

    public function update(array $input)
    {
        $customer = Customer::find($input['id']);
        // dd($input, $customer);
        unset($input['id']);

        if ($customer->update($input)) {
            return $customer;
        }

        return false;
    }

    public function customerDeatailsSave($input)
    {
        if ($customerDetails = CustomerDetails::create($input)) {
            return $customerDetails;
        }

        return false;
    }

    public function customerDetailsUpdate(array $input)
    {
        if ($input['customer_id']) {
            $customerDetails = CustomerDetails::where('customer_id', $input['customer_id'])->first();

            if (!$customerDetails) {
                unset($input['id']);
                $customerDetails = CustomerDetails::create($input);
                return $customerDetails;
            } else {
                $customerDetails->update($input);
                // Refresh the model to get the updated values
                $customerDetails->refresh();
                return $customerDetails;
            }
        }

        return false;
    }

    public function customerDetailsSave(array $input)
    {
        $customerDetails = CustomerDetails::create($input);
        return $customerDetails;
    }

    public function getaddressById($addressId)
    {

        return   $customerDetails = CustomerDetails::where('id',$addressId)->first();

    }

    public function customerDetailsGetAll($customerId)
    {
        return CustomerDetails::where('customer_id',$customerId)->get();
    }

    public function searchCustomers($keyword)
    {
        $customer = Customer::where('status', 1)
            ->where(function ($q) use ($keyword) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) like '%" . $keyword . "%'")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%");
            });

        return $customer->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function deleteCustomerDetails($id)
    {
        return CustomerDetails::find($id)->delete();
    }

    public function getCustomerByPhoneNumber($phone)
    {
        return Customer::where('phone',$phone)->first();
    }

}
