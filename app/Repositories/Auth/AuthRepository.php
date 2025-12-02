<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerOtp;
use Carbon\Carbon;
class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Create new user based on signup flow.
     *
     * @param  array  $details
     * @return User $user|false
     */
    public function createUser($details)
    {
        $user = new User();

        foreach ($details as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return $user;
    }

    /**
     * Retrive user data by phone number.
     * NB: Phone number is unique
     *
     * @param  string  $phone
     * @return User $user|false
     */
    public function getUserByPhone($phone)
    {
        $user = User::where('phone', $phone)->first();

        return $user;
    }

    /**
     * Create api token for User.
     * Used Login process.
     *
     * @param  User  $user
     * @param  string  $deviceIdentity
     * @return string $token
     */
    public function createToken($user, $deviceIdentity)
    {
        return $user->createToken($deviceIdentity)->plainTextToken;
    }

    /**
     * Invalidate API access token.
     * Used for logout
     *
     * @param  User  $user
     * @return bool
     */
    public function revokeToken($user)
    {
        return $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }

    /**
     * Invalidate all access token against the user.
     *
     * @param  User  $user
     * @return bool
     */
    public function revokeAllTokens(User $user)
    {
        // TODO code...
    }

    public function getCustomerByEmail($email)
    {
        return Customer::where('email', $email)->first();
    }


    public function getCustomerByPhone($phone)
    {
        return Customer::where('phone', $phone)->first();
    }

    public function generateOtp($data)
    {
        return CustomerOtp::create([
            // 'otp' => rand(1000, 9999),
            'otp' => '1234',
            'expire_at' => Carbon::now()->addMinutes(10),
            'ip_address' => $data['ip'],
            'phone_number' => $data['phone_number'],
        ]);
    }

    public function verifyOtp($request)
    {
        $otp = CustomerOtp::where('otp', $request->otp)
            ->where('ip_address', $request->ip());

        if (isset($request->phone_number) && $request->phone_number != null) {
            $otp = $otp->where('phone_number', $request->phone_number);
        }

        if (isset($request->email) && $request->email != null) {
            $otp = $otp->where('email', $request->email);
        }

        return $otp->latest()->first();
    }

    public function getCustomerByPhoneNumber($phone)
    {
        return Customer::where('phone', $phone)->first();
    }
}
