<?
namespace App\Auth;

use App\Models\Company\EmployeePortalAccess;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class EmployeePortalUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return EmployeePortalAccess::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return EmployeePortalAccess::where('access_token', $token)
            ->where('is_active', true)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Não implementado para este provider
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || !isset($credentials['email'])) {
            return null;
        }

        return EmployeePortalAccess::where('email', $credentials['email'])
            ->where('is_active', true)
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->password);
    }
}
