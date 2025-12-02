<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\GenerateOtpRequest;
use App\Http\Requests\Api\Auth\OtpVerifyRequest;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Repositories\Auth\AuthRepositoryInterface as AuthRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use stdClass;
use Carbon\Carbon;
class AuthController extends Controller
{

    public function register(RegisterRequest $request, CustomerRepository $customerRepo, AuthRepository $authRepo)
    {
        try {
            $input = [
                'first_name' => $request->first_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->register_password),
                'status' => 'active',
            ];

            // Attempt to save the customer
            $customer = $customerRepo->save($input);

            // Attempt to create a token
            $access_token = $authRepo->createToken($customer, 'customer_registration');

            // Check if customer is successfully saved
            if ($customer) {
                $response = ['status' => true, 'message' => 'User successfully registered'];
                return response()->json($response, 200);
            } else {
                // Handle case where saving customer failed
                $response = ['status' => false, 'message' => 'Failed to register user'];
                return response()->json($response, 200);
            }
        }catch (\Exception $e) {
            // Handle other exceptions
            $response = ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
            return response()->json($response, 200);
        }
    }

    public function Login(AuthRepository $authRepo, Request $request)
    {

        $customer = $authRepo->getCustomerByPhone($request->phone);

        if (($customer != null) && (Hash::check($request->password, $customer->password))) {
            Auth::login($customer);

            $token = $authRepo->createToken($customer, $request->deviceIdentity);
            $response = ['status' => true, 'data' => ['customer' => $customer, 'access_token' => $token], 'message' => 'Customer verified successfully'];

            return response()->json($response, 200);
        } else {
            $response = ['status' => false, 'message' => 'Customer verification failed'];

            return response()->json($response, 200);
        }
    }

    public function logout(AuthRepository $authRepo)
    {
        $customer = auth('sanctum')->user();

        $status = $authRepo->revokeToken($customer);

        if (!empty($status)) {
            $response = ['status' => true, 'message' => 'Logout successfully'];

            return response()->json($response, 200);
        } else {
            $response = ['status' => false, 'message' => 'Logout faild'];

            return response()->json($response, 200);
        }
    }

    public function OtpGenerate(GenerateOtpRequest $request, AuthRepository $authRepo)
    {
        try {
            $input['phone_number'] = $request->phone_number;
            $input['ip'] = $request->ip();
            $otp = $authRepo->generateOtp($input);
            $otp = $otp->otp;
            session()->put('otp', $otp);

            $data = compact('otp');
            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function OtpVerify(OtpVerifyRequest $request, AuthRepository $authRepo, CustomerRepository $customerRepo)
    {
        try {
            $otp = $authRepo->verifyOtp($request);
            $now = Carbon::now();

            if (!$otp) {
                $response = ['status' => false, 'message' => 'Your OTP is not correct'];
            } elseif ($otp && $now->isAfter($otp->expire_at)) {
                $response = ['status' => false, 'message' => 'Your OTP has been expired'];
            } else {
                $response = ['status' => true, 'message' => 'Otp verified successfully'];
                $customer = $authRepo->getCustomerByPhoneNumber($request->phone_number);

                if ($customer) {
                    if ($customer->status == 'inactive') {
                        $response = ['status' => false, 'message' => 'User is inactive Please contact admin'];

                        return response()->json($response, 200);
                    }
                    // $token = $authRepo->createToken($customer, 'verify_otp');

                    if (!$customer->phone_verified_at) {
                        $customerUpdate = [
                            'id' => $customer->id,
                            'phone_verified_at' => Carbon::now(),
                        ];
                        $customerUpdate = $customerRepo->update($customerUpdate);
                    }

                    $response['data'] = ['customer' => $customer];
                }
            }

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request, CustomerRepository $customerRepo, AuthRepository $authRepo)
    {
        try {
            $customer = $customerRepo->getCustomerByPhoneNumber($request->phone_number);
            if ($customer) {
                if ($request->new_password != null && $request->confirm_password) {
                    $updateCustomer = [
                        'id' => $customer->id,
                        'password' => Hash::make($request->new_password),
                    ];
                    $customer = $customerRepo->update($updateCustomer);
                    $response['data'] = [ 'customer' => $customer];
                    return $response = ['status' => true, 'data' => $response['data'], 'message' => 'Success'];
                } else {
                    $response = ['status' => true, 'message' => 'OTP verified'];
                }
            }
            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }
}
