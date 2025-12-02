<?php

namespace App\Http\Controllers\Admin\Includes\Customer;

use App\Http\Requests\Admin\Customer\CustomerAddressCreateRequest;
use App\Http\Requests\Admin\Customer\CustomerAddressUpdateRequest;
use App\Http\Requests\Admin\Customer\CustomerDeleteRequest;
use App\Http\Requests\Admin\Customer\CustomerListDataRequest;
use App\Repositories\Country\CountryRepositoryInterface as CountryRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\State\StateRepositoryInterface as StateRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait Address
{
    public function addressList(CustomerListDataRequest $request, CustomerRepository $customerRepo)
    {
        $customersAddress = $customerRepo->getCustomerAddress($request->all());
        $dataTableJSON = DataTables::of($customersAddress)
            ->addIndexColumn()
            ->editColumn('name', function ($customersAddress) {
                $data['url'] = request()->user()->can('customer_update') ? route('admin_customer_edit', ['id' => $customersAddress->customer_id]) : '';
                $data['text'] = $customersAddress->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('action', function ($customersAddress) use ($request) {
                $data['view_address_url'] = request()->user()->can('customer_update') ? route('admin_customer_address_view', ['id' => $customersAddress->customer_id]) : '';
                $data['delete_url'] = request()->user()->can('customer_delete') ? route('admin_customer_address_delete', ['id' => $customersAddress->id]) : '';
                $data['customersAddress'] = $customersAddress;

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function addCustomerAddress(Request $request, CountryRepository $countryRepo)
    {
        $customerId = $request->id;
        $country = $countryRepo->getDefaultCountry();
        $responce['html'] = (string) view('admin.customer.addCustomerAddress', compact('customerId', 'country'));
        $responce['scripts'][] = (string) mix('js/admin/customer/addCustomerAddress.js');

        return $responce;
    }

    public function saveCustomerAddress(CustomerAddressCreateRequest $request, CustomerRepository $customerRepo)
    {
        $customerAddressInputData = [
            'customer_id' => $request->customer_id,
            'name_prefix' => $request->name_prefix,
            'name_suffix' => $request->name_suffix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'street_address' => $request->street_address,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city' => $request->city,
            'postel_code' => $request->postel_code,
            'contact' => $request->contact,

        ];
        $customerAddress = $customerRepo->customerAddressSave($customerAddressInputData);

        return response()->json(['status' => 1, 'message' => 'Customer Address Added Successfully']);
    }

    public function customerAddressView(
        Request $request,
        CustomerRepository $customerRepo,
        CountryRepository $countryRepo,
        StateRepository $stateRepo
    ) {
        $customerAddess = $customerRepo->customerAddress($request->id);
        $old = [];

        if (old('country', isset($customerAddess->country_id))) {
            $old['country'] = $countryRepo->getCountry(old('country', $customerAddess->country_id));
        }

        if (old('state', isset($customerAddess->state_id))) {
            $old['state'] = $stateRepo->getState(old('state', $customerAddess->state_id));
        }
        $responce['html'] = (string) view('admin.customer.editCustomerAddress', compact('customerAddess', 'old'));
        $responce['scripts'][] = (string) mix('js/admin/customer/editCustomerAddress.js');

        return $responce;
    }

    public function updateCustomerAddress(CustomerAddressUpdateRequest $request, CustomerRepository $customerRepo)
    {
        $updateData = [
            'id' => $request->id,
            'name_prefix' => $request->name_prefix,
            'name_suffix' => $request->name_suffix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'street_address' => $request->street_address,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city' => $request->city,
            'postel_code' => $request->postel_code,
            'contact' => $request->contact,
        ];
        $customerAddress = $customerRepo->customerAddressUpdate($updateData);

        return response()->json(['status' => 1, 'message' => 'Customer Address Updated Successfully']);
    }

    public function deleteCustomerAddress(CustomerRepository $customerRepo, CustomerDeleteRequest $request)
    {
        $customerAddress = $customerRepo->customerAddress($request->id);
       
        if ($customerRepo->deleteCustomerAddress($customerAddress->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'Customer Address deleted successfully']);
            } else {
                return redirect()->route('admin_customer_list')->with('success', 'Customer Address deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_customer_list')->with('success', 'Failed to delete');
        }
    }

    public function customerAddress(Request $request, CustomerRepository $customerRepo)
    {
        $customerAddress = $customerRepo->getCustomerAddress($request->all());
        $billingAddress = view('admin.order.orderAddress', compact('customerAddress'))->with('address_type', 'billing_address')->render();
        $shippingAddress = view('admin.order.orderAddress', compact('customerAddress'))->with('address_type', 'shipping_address')->render();

        return response()->json(['html' => ['billing_address' => $billingAddress, 'shipping_address' => $shippingAddress]]);
    }
}
