<?
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalEmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // dd('asas');
        // Verificar se está autenticado no guard do portal
        if (!Auth::guard('employee_portal')->check()) {
            return redirect()->route('portal.login');
        }

        $portalUser = Auth::guard('employee_portal')->user();

        // Verificar se o acesso está ativo
        if (!$portalUser->is_active) {
            Auth::guard('employee_portal')->logout();
            return redirect()->route('portal.access-denied')
                ->with('reason', 'Conta de acesso ao portal desativada');
        }

        // Verificar se o funcionário ainda está ativo
        if (!$portalUser->employee || !$portalUser->employee->is_active) {
            Auth::guard('employee_portal')->logout();
            return redirect()->route('portal.access-denied')
                ->with('reason', 'Funcionário não encontrado ou inativo');
        }

        // Adicionar dados à request
        $request->merge([
            'current_portal_user' => $portalUser,
            'current_employee' => $portalUser->employee
        ]);

        return $next($request);
    }
}