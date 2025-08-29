<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  string $permission): Response
    {
       
      
        if (!Auth::check()) {
            return $this->redirectToLogin($request);
        }

        $user = Auth::user();

        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return $this->redirectToLogin($request, 'Sua conta está inativa.');
        }

        // Super Admin e Company Admin têm todas as permissões
        if ($user->isSuperAdmin() || $user->isCompanyAdmin()) {
            return $next($request);
        }

        // Check if user has the required permission usando o trait HasPermissions
        if (!$user->hasPermission($permission)) {
            // Log da negação de permissão
            // if (class_exists('\App\Services\ActivityLoggerService')) {
            //     $logger = app(\App\Services\ActivityLoggerService::class);
            //     $logger->log(
            //         'permission_denied',
            //         "Usuário {$user->name} tentou acessar {$request->route()->getName()} sem permissão: {$permission}",
            //         'security',
            //         'warning'
            //     );
            // }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Você não tem permissão para realizar esta ação.',
                    'permission' => $permission,
                    'route' => $request->route()->getName(),
                    'user_permissions' => $user->permissions,
                ], 403);
            }

            // dd($user->permissions);
            return redirect()->route('company.my-permissions')
                ->with('error', "Você não tem permissão para realizar esta ação. Permissão necessária: {$permission}");
            // abort(403, "Você não tem permissão para realizar esta ação. Permissão necessária: {$permission}");
        }

        return $next($request);
    }

     private function redirectToLogin(Request $request, string $message = null)
    {
        $loginRoute = match (true) {
            $request->routeIs('system.*') => 'system.login',
            $request->routeIs('company.*') => 'company.login',
            default => 'company.login'
        };

        $redirect = redirect()->route($loginRoute);
       
        if ($message) {
            $redirect->with('error', $message);
        }

        return $redirect;
    }
}
