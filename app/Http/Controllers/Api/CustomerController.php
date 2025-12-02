<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\User\PasswordChangeRequest;
use App\Http\Requests\Api\User\SelectAddressRequest;
use App\Http\Requests\Api\User\DeleteAddressRequest;
use App\Http\Requests\Api\User\CustomerEditRequest;
use App\Http\Requests\Api\User\CustomerAddressEdit;
use App\Repositories\Auth\AuthRepositoryInterface as AuthRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function getCustomer(Request $request, CustomerRepository $customerRepo)
    {
        $customer = auth('sanctum')->user();

        return response()->json(['status' => true, 'user' => $customer], 200);
    }

    public function changeUserPassword(PasswordChangeRequest $request, CustomerRepository $customerRepo)
    {
        if ($request->has('current_password')) {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return response()->json(['status' => false, 'message' => 'The current password is incorrect.'], 200);
            }

            if ($request->current_password == $request->new_password) {
                return response()->json(['status' => false, 'message' => 'New password must be different from current'], 200);
            }
        }
        $data['password'] = Hash::make($request->new_password);
        $customer = $customerRepo->updateCustomer(auth()->user()->id, $data);

        return response()->json(['status' => true, 'message' => 'User password updated successfully'], 200);

    }

    public function selectAddress(SelectAddressRequest $request, CustomerRepository $customerRepo)
    {

        $data = [
            'customer_details_id' => $request->address_id,
        ];
        $customer = $customerRepo->updateCustomer(auth()->user()->id, $data);

        return response()->json(['status' => true, 'message' => 'Address Selected successfully'], 200);

    }

    public function getSelectedAddress(Request $request, CustomerRepository $customerRepo)
    {

        $customer = auth('sanctum')->user();
        $address =  $customerRepo->getaddressById($customer->customer_details_id);

        if(isset($address)){
            $customerDetails = [
                'id' => $address->id,
                'customer_id' => $address->customer_id,
                'street' => $address->street,
                'address_line1' => $address->address_line1,
                'number' => $address->number,
            ];
            return response()->json(['status' => true, 'address' =>  $customerDetails, 'message' => 'Address Selected successfully'], 200);
        }else{
            return response()->json(['status' => true, 'address' => Null,'message' => 'Address  Not Exist'], 200);
        }

    }

    public function deleteAddress(DeleteAddressRequest $request, CustomerRepository $customerRepo)
    {
        $address =  $customerRepo->getaddressById($request->address_id);

        if(isset($address)){
          $address =  $customerRepo->deleteCustomerDetails($request->address_id);

          return response()->json(['status' => true, 'message' => 'Address Deleted successfully'], 200);
        }else{
          return response()->json(['status' => true, 'message' => 'Address not Exist'], 200);
        }
    }

    public function addAdress(Request $request, CustomerRepository $customerRepo)
    {
        $customer = auth('sanctum')->user();

        if ($customer->id) {
            $customerDetailsUpdateData = [
                'customer_id' => $customer->id,
                'street' => $request->street,
                'address_line1' => $request->address,
                'number' => $request->phone,
            ];
            $customer = $customerRepo->customerDetailsSave($customerDetailsUpdateData);

            if( $customer->customer_details_id == null){
                $data = [
                    'customer_details_id' =>  $customer->id,
                ];
                $customer = $customerRepo->updateCustomer(auth()->user()->id, $data);
            }
        }else{
        return response()->json(['status' => true, 'message' => 'User not exist'], 200);
        }
        return response()->json(['status' => true, 'message' => 'User Adress Added Successfully'], 200);

    }

    public function listAdress(Request $request, CustomerRepository $customerRepo)
    {
        $customer = auth('sanctum')->user();
        $language_type = $request->language_type;
        if ($customer->id) {

            $customers = $customerRepo->customerDetailsGetAll($customer->id);

            $customer = $customers->map(function ($items, $key) use ($language_type,  $customer) {
                    return [
                        'id' => $items->id,
                        'street' => $items->street,
                        'address_line1' => $items->address_line1,
                        'number' => $items->number,
                        'is_selected' => isset($customer->customer_details_id) && $customer->customer_details_id === $items->id ? true : false,
                    ];
            });
        }else{
        return response()->json(['status' => true,'address' => Null, 'message' => 'address not exist'], 200);
        }
        return response()->json(['status' => true, 'address' => $customer], 200);
    }

    public function customerUpdate(CustomerEditRequest $request, CustomerRepository $customerRepo)
    {
        try {
            $customer = [];
            $customerId = auth('sanctum')->user()->id;
            $customer = $customerRepo->getCustomer($customerId);
            $customerUpdate =
                [
                    'id' => $customerId,
                    'first_name' => $request->first_name ? $request->first_name : $customer->first_name,
                    'email' => $request->email ? $request->email : $customer->email,
                    'phone' => ($request->mobile_verified == 1 && $request->phone) ? $request->phone : $customer->phone,
                ];
            $customerUpdate = $customerRepo->update($customerUpdate);
            $customer = $customerRepo->getCustomer($customerId);
            $data = compact('customer');

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function updateAddress(CustomerAddressEdit $request,CustomerRepository $customerRepo)
    {
        try {
            $customer = [];
            $customerId = auth('sanctum')->user()->id;
            $customer = $customerRepo->getCustomer($customerId);
            $customerAddress = $customerRepo->getaddressById($request->customer_address_id);
            $customerAddressUpdate =
                [
                    'id' => $customerAddress->id,
                    'customer_id' => $customer->id,
                    'address_line1' => $request->address ? $request->address : $customerAddress->address,
                    'street' => $request->street ? $request->street : $customerAddress->street,
                    'number' =>  $request->phone ? $request->phone : $customerAddress->phone,
                ];
            $customerAddressUpdate = $customerRepo->customerDetailsUpdate($customerAddressUpdate);
            $customer = $customerRepo->getaddressById($request->customer_address_id);
            $data = compact('customer');

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

        }
    }

    public function updateProfileImage(Request $request,CustomerRepository $customerRepo)
    {
        try {
            $customer = [];
            $customerId = auth('sanctum')->user()->id;
            $updateData = [
                'id' => $customerId,
            ];
            if ($request->hasFile('profile_picture')) {
                $filePath = 'customer/profile_picture';
                $fileName = Storage::disk('grocery')->putFile($filePath, $request->file('profile_picture'));
                $updateData['profile_picture'] = $fileName;
            }
            $customer = $customerRepo->update($updateData);
            $customer = $customerRepo->getCustomer($customerId);

            $data = compact('customer');

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

        }
    }
}
