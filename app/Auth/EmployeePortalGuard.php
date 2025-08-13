<?
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

        // dd($user);
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
        $this->updateSession($user->access_token);
        $user->recordLogin();
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
}
