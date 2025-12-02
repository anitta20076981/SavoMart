<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Create new user based on signup flow.
     *
     * @param  array  $request
     * @return User $user|false
     */
    public function createUser($details);

    /**
     * Retrive user data by phone number.
     * NB: Phone number is unique
     *
     * @param  string  $phone
     * @return User $user|false
     */
    public function getUserByPhone($phone);

    /**
     * Create api token for User.
     * Used Login process.
     *
     * @param  User  $user
     * @param  string  $deviceIdentity
     * @return string $token
     */
    public function createToken($user, $deviceIdentity);

    /**
     * Invalidate API access token.
     * Used for logout
     *
     * @param  User  $user
     * @return bool
     */
    public function revokeToken($user);

    /**
     * Invalidate all access token against the user.
     *
     * @param  User  $user
     * @return bool
     */
    public function revokeAllTokens(User $user);

    public function getCustomerByEmail($email);

    public function getCustomerByPhone($phone);

    public function generateOtp($data);

    public function verifyOtp($request);

    public function getCustomerByPhoneNumber($phone);
}
