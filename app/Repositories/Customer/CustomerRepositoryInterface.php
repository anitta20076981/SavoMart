<?php

namespace App\Repositories\Customer;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function getForDatatable($data);

    public function createCustomer($details);

    public function updateCustomer(Customer $customer, $details);

    public function getCustomer($customerId);

    public function getAllCustomers();

    public function deleteCustomer($customerId);

    public function createRole($data, $permission);

    public function updateRole($roleId, $data, $permissions);

    public function roleStatusUpdate($roleId);

    public function customerStatusUpdate($customerId);

    public function getRole($roleId);

    public function getAllRoles($data);

    public function getAllRole();

    public function deleteRole($roleId);

    public function roleCustomers($id);

    public function getPermissionTemplate();

    public function searchRole($keyword);

    public function getAllCustomersexcepetSuperAdmin($data);

    public function registerDevice(array $input);

    public function save($input);

    public function update(array $input);

    public function customerDeatailsSave($input);

    public function customerDetailsUpdate(array $input);

    public function getaddressById($addressId);

    public function searchCustomers($keyword);

    public function deleteCustomerDetails($id);

    public function getCustomerByPhoneNumber($phone);

}
