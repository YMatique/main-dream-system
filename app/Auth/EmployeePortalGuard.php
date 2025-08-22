<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class EmployeePortalGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $lastAttempted;
    
    /**
     * The name of the query string item from the "intended" URL.
     *
     * @var string
     */
    protected $inputKey = 'intended';

    /**
     * Get the URL the user should be redirected to when they are not authenticated.
     */
    public function redirectTo()
    {
        return route('portal.login');
    }

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->retrieveByToken(null, $token);
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user, $credentials);
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);
            return true;
        }

        return false;
    }

    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null &&
               $user->is_active &&
               $this->provider->validateCredentials($user, $credentials);
    }

    public function login($user, $remember = false)
    {
        // Gerar token se não existir
        if (empty($user->access_token)) {
            $user->generateAccessToken();
        }
        
        $this->updateSession($user->access_token);
        
        // Registrar login se o método existir
        if (method_exists($user, 'recordLogin')) {
            $user->recordLogin();
        }
        
        $this->setUser($user);
    }

    public function logout()
    {
        $this->clearUserDataFromStorage();
        $this->user = null;
    }

    protected function updateSession($token)
    {
        $this->request->session()->put('employee_portal_token', $token);
        $this->request->session()->regenerate();
    }

    protected function clearUserDataFromStorage()
    {
        $this->request->session()->forget('employee_portal_token');
    }

    protected function getTokenForRequest()
    {
        return $this->request->session()->get('employee_portal_token');
    }

    // Métodos adicionais necessários para a interface Guard
    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        if ($user = $this->user()) {
            return $user->getAuthIdentifier();
        }
    }
}