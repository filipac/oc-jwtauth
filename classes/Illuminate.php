<?php
/**
 * Created by PhpStorm.
 * User: filipac
 * Date: 27/10/2017
 * Time: 23:29
 */

namespace Vdomah\JWTAuth\Classes;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class Illuminate implements Auth
{
    /**
     * The authentication guard.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Constructor.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Check a user's credentials.
     *
     * @param  array  $credentials
     *
     * @return bool
     */
    public function byCredentials(array $credentials)
    {
        try {
            $user = $this->auth->findUserByCredentials($credentials);
            if($user) {
                $this->auth->setUser($user);
            }
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Authenticate a user via the id.
     *
     * @param  mixed  $id
     *
     * @return bool
     */
    public function byId($id)
    {
        try {
            $user = $this->auth->findUserById($id);
            if($user) {
                $this->auth->setUser($user);
            }
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the currently authenticated user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->auth->getUser();
    }
}