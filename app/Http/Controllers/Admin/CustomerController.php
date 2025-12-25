<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Includes\Customer\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerAddRequest;
use App\Http\Requests\Admin\Customer\CustomerCreateRequest;
use App\Http\Requests\Admin\Customer\CustomerDeleteRequest;
use App\Http\Requests\Admin\Customer\CustomerEditRequest;
use App\Http\Requests\Admin\Customer\CustomerListDataRequest;
use App\Http\Requests\Admin\Customer\CustomerListRequest;
use App\Http\Requests\Admin\Customer\CustomerUpdateRequest;
use App\Repositories\Country\CountryRepositoryInterface as CountryRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Quote\QuoteRepositoryInterface as QuoteRepository;
use App\Repositories\State\StateRepositoryInterface as StateRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use Address;

    public function list(CustomerListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Customer'],
        ];

        return view('admin.customer.listCustomers', compact('breadcrumbs'));
    }

    public function table(CustomerListDataRequest $request, CustomerRepository $customerRepo)
    {
        $customers = $customerRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($customers)
            ->addIndexColumn()
            ->editColumn('first_name', function ($customer) {
                $data['url'] = request()->user()->can('customer_update') ? route('admin_customer_edit', ['id' => $customer->id]) : '';
                $data['text'] = $customer->first_name;

                return view('admin.elements.listLink', compact('data'));
            })
        // ->addColumn('vendor_status', function ($customer) {
        //     $vendorStatus = ($customer->is_vendor == 0) ? 'No' : 'Yes';

        //     return view('admin.customer.listVendorStatue')->with('data', $vendorStatus);
        // })
            ->addColumn('status', function ($customer) {
                return view('admin.elements.listStatus')->with('data', $customer);
            })
            ->addColumn('approve', function ($feedback) {
                $data['url']="approve_url";
                $data['id']=$feedback->id;
                return view('admin.elements.listApproval')->with('data', $feedback);
            })
            ->addColumn('action', function ($customer) use ($request) {
                $data['edit_url'] = request()->user()->can('customer_update') ? route('admin_customer_edit', ['id' => $customer->id]) : '';
                $data['delete_url'] = request()->user()->can('customer_delete') ? route('admin_customer_delete', ['id' => $customer->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(CustomerAddRequest $request, CountryRepository $countryRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_customer_list', 'name' => 'Customer', 'permission' => 'customer_read'],
            ['name' => 'Add Customer'],
        ];
        $businessType = ['manufacturer', 'supplier', 'trader', 'whole saler', 'business service'];
        $old = [];
        $old['country_id'] = $old['country_code_id'] = $countryRepo->getDefaultCountry();

        return view('admin.customer.addCustomer', compact('breadcrumbs', 'businessType', 'old'));
    }

    public function create(CustomerCreateRequest $request, CustomerRepository $customerRepo)
    {
        $inputData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'country_code_id' => $request->country_code_id,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'phone_verified_at' => date('Y-m-d H:i:s'),
            'approve' => 1,
        ];

        if ($request->hasFile('profile_image')) {
            $filePath = 'customer/profile_image';
            $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('profile_image'));
            $inputData['profile_picture'] = $fileName;
        }

        $customer = $customerRepo->save($inputData);

        // $customerRepo->update(['id' => $customer->id, 'country_code' => $customer->countryCode->country_code]);

        // $customerAccountInputData = [
        //     'customer_id' => $customer->id,
        //     'name' => $request->first_name,
        //     'account_no' => $request->account_no,
        //     'bank_name' => $request->bank_name,
        //     'ifsc' => $request->ifsc,
        //     'branch_name' => $request->branch_name,
        //     'address' => $request->address,
        // ];
        // $customerAccount = $customerRepo->customerAccountSave($customerAccountInputData);

        $customerDetailsInputData = [
            'customer_id' => $customer->id,
            'street' => $request->street,
            'address_line1' => $request->address_line1,
            'number' => $request->number,
        ];

        // if ($request->hasFile('signature')) {
        //     $filePath = 'customer/signature';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('signature'));
        //     $customerDetailsInputData['signature'] = $fileName;
        // }

        // if ($request->hasFile('vendor_document')) {
        //     $filePath = 'customer/vendor_document';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('vendor_document'));
        //     $customerDetailsInputData['vendor_document'] = $fileName;
        // }

        // if ($request->hasFile('gst_certificate')) {
        //     $filePath = 'customer/gst_certificate';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('gst_certificate'));
        //     $customerDetailsInputData['gst_certificate'] = $fileName;
        // }

        $customer = $customerRepo->customerDeatailsSave($customerDetailsInputData);

        // $customerSubscriptionInputData = [
        //     'customer_id' => $customer->id,
        //     'subscription_plan_id' => 1,
        //     'from' => date('Y-m-d'),
        //     'to' => date('Y-m-d', strtotime('+1 year')),
        //     'orders_processed' => 0,
        //     'products_featured' => 0,
        //     'rfq_processed' => 0,
        //     'rfq_submitted' => 0,
        //     'payment_method_id' => 0,
        //     'payment_reference' => null,
        //     'payment_status' => 'pending',
        //     'status' => 'active',
        // ];
        // $customer = $customerRepo->customerSubscriptionInputData($customerSubscriptionInputData);

        return redirect()
            ->route('admin_customer_list')
            ->with('success', 'Customer added successfully');
    }

    public function edit(
        CustomerEditRequest $request,
        CustomerRepository $customerRepo,
        CountryRepository $countryRepo,
        StateRepository $stateRepo
    ) {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_customer_list', 'name' => 'Customer', 'permission' => 'customer_read'],
            ['name' => 'Customer Details'],
        ];
        $customer = $customerRepo->getCustomer($request->id);

        $old = [];

        if (old('country_id', isset($customer->customerDetails->country_id))) {
            $old['country_id'] = $countryRepo->getCountry(old('country', $customer->customerDetails->country_id));
        }

        if (old('state_id', isset($customer->customerDetails->state_id))) {
            $old['state_id'] = $stateRepo->getState(old('state_id', $customer->customerDetails->state_id));
        }
        // $selectedGoodsCategories = isset($customer->customerDetails->goods_categories);

        return view('admin.customer.editCustomer', compact('customer', 'breadcrumbs', 'old'));
    }

    public function update(CustomerUpdateRequest $request, CustomerRepository $customerRepo)
    {
        $updateData = [
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'country_code_id' => $request->country_code_id,
            'status' => $request->status,
        ];

        if ($request->hasFile('profile_image')) {
            $filePath = 'customer/profile_image';
            $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('profile_image'));
            $updateData['profile_picture'] = $fileName;
        } elseif ($request->profile_image_remove == 1) {
            $updateData['profile_picture'] = '';
        }
        $customer = $customerRepo->update($updateData);
        // $customerRepo->update(['id' => $customer->id, 'country_code' => $customer->countryCode->country_code]);

        // $customerAccountUpdateData = [
        //     'id' => $request->id,
        //     'customer_id' => $customer->id,
        //     'name' => $request->first_name,
        //     'account_no' => $request->account_no,
        //     'bank_name' => $request->bank_name,
        //     'ifsc' => $request->ifsc,
        //     'branch_name' => $request->branch_name,
        //     'address' => $request->address,
        // ];
        // $customerAccount = $customerRepo->customerAccountUpdate($customerAccountUpdateData);

        $customerDetailsUpdateData = [
            'customer_id' => $customer->id,
            'street' => $request->street,
            'address_line1' => $request->address_line1,
            'number' => $request->number,
        ];
        // if ($request->hasFile('signature')) {
        //     $filePath = 'customer/signature';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('signature'));
        //     $customerDetailsUpdateData['signature'] = $fileName;
        // } elseif ($request->has('signature_remove') && $request->signature_remove) {
        //     $customerDetailsUpdateData['signature'] = '';
        // }

        // if ($request->hasFile('gst_certificate')) {
        //     $filePath = 'customer/gst_certificate';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('gst_certificate'));
        //     $customerDetailsUpdateData['gst_certificate'] = $fileName;
        // } elseif ($request->has_gst == 1 && $request->hasFile('gst_certificate') == false) {
        //     $customerDetailsUpdateData['gst_certificate'] = $request->gst_certificate;
        // } elseif ($request->has('gst_certificate') && $request->gst_certificate) {
        //     $customerDetailsUpdateData['gst_certificate'] = '';
        // }

        // if ($request->hasFile('vendor_document')) {
        //     $filePath = 'customer/vendor_document';
        //     $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('vendor_document'));
        //     $customerDetailsUpdateData['vendor_document'] = $fileName;
        // } elseif ($request->has('vendor_document_remove') && $request->vendor_document_remove) {
        //     $customerDetailsUpdateData['vendor_document'] = '';
        // }

        $customer = $customerRepo->customerDetailsUpdate($customerDetailsUpdateData);

        return redirect()
            ->route('admin_customer_list')
            ->with('success', 'Customer updated successfully');
    }

    public function delete(CustomerRepository $customerRepo, CustomerDeleteRequest $request)
    {
        $customer = $customerRepo->getCustomer($request->id);

        if ($customerRepo->deleteCustomer($request->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'Customer deleted successfully']);
            } else {
                return redirect()->route('admin_customer_list')->with('success', 'Customer deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_customer_list')->with('success', 'Failed to delete');
        }
    }

    public function quoteRequestTable(CustomerListDataRequest $request, QuoteRepository $quoteRepo)
    {
        $quoteRequest = $quoteRepo->getForDataTable($request->all());
        $dataTableJSON = DataTables::of($quoteRequest)
            ->addIndexColumn()
            ->editColumn('request_number', function ($quoteRequest) {
                return $quoteRequest->request_number;
            })
            ->editColumn('category', function ($quoteRequest) {
                return $quoteRequest->category->name;
            })
            ->editColumn('product', function ($quoteRequest) {
                return $quoteRequest->product ? $quoteRequest->product->name : '';
            })
            ->make();

        return $dataTableJSON;
    }

    public function quoteTable(CustomerListDataRequest $request, QuoteRepository $quoteRepo)
    {
        $quote = $quoteRepo->getForQuoteDataTable($request->all());
        $dataTableJSON = DataTables::of($quote)
            ->addIndexColumn()
            ->editColumn('product', function ($quote) {
                return $quote->product ? $quote->product->name : '';
            })
            ->editColumn('comments', function ($quote) {
                return $quote->comments ? $quote->comments : '';
            })
            ->editColumn('status', function ($quote) {
                return view('admin.quote.listStatus')->with('data', $quote);
            })
            ->make();

        return $dataTableJSON;
    }

    public function approve(Request $request, CustomerRepository $customerRepo)
    {
        $inputData = [
            'id' => $request->id,
            'approve' => $request->value,
        ];

        $customer = $customerRepo->update($inputData);

        return (true);
    }
}