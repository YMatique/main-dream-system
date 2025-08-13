<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Company\EmployeePortalAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PortalAuthController extends Controller
{
      /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.portal-login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
          $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Primeiro verificar se o usuário existe e está ativo
        $portalUser = EmployeePortalAccess::where('email', $credentials['email'])
            ->where('is_active', true)
            ->first();

        if (!$portalUser) {
            throw ValidationException::withMessages([
                'email' => 'Usuário não encontrado ou conta inativa.',
            ]);
        }

        // Verificar se o funcionário está ativo
        if (!$portalUser->employee || !$portalUser->employee->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Funcionário não encontrado ou inativo.',
            ]);
        }

        // Tentar fazer login usando o guard do portal
        if (Auth::guard('employee_portal')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Registrar login
            $portalUser->recordLogin();
            
            // Log do acesso
            \Log::info('Portal do funcionário acessado', [
                'portal_access_id' => $portalUser->id,
                'employee_id' => $portalUser->employee_id,
                'employee_name' => $portalUser->employee_name,
                'company' => $portalUser->company_name,
                'email' => $portalUser->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended(route('employee.portal'));
        }

        throw ValidationException::withMessages([
            'email' => 'As credenciais fornecidas são inválidas.',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $portalUser = Auth::guard('employee_portal')->user();
        
        // Log do logout
        if ($portalUser) {
            \Log::info('Logout do portal do funcionário', [
                'portal_access_id' => $portalUser->id,
                'employee_name' => $portalUser->employee_name,
                'ip' => $request->ip()
            ]);
        }

        Auth::guard('employee_portal')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Página de acesso negado
     */
    public function accessDenied()
    {
        return view('auth.portal-access-denied');
    }
}
