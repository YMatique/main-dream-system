<?php
if (!function_exists('hasPermission')) {
    /**
     * Helper global para verificar permissão
     */
    function hasPermission(string $permission): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // Super Admin e Company Admin têm todas as permissões
        if (in_array($user->user_type, ['super_admin', 'company_admin'])) {
            return true;
        }

        return $user->hasPermission($permission);
    }
}

if (!function_exists('canAccessForm')) {
    /**
     * Helper para verificar acesso a formulário
     */
    function canAccessForm(int $formNumber): bool
    {
        return hasPermission("forms.form{$formNumber}.access");
    }
}

if (!function_exists('canEvaluateDepartment')) {
    /**
     * Helper para verificar se pode avaliar departamento
     */
    function canEvaluateDepartment(int $departmentId): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // Admin sempre pode
        if (in_array($user->user_type, ['super_admin', 'company_admin'])) {
            return true;
        }

        return $user->canEvaluateDepartment($departmentId);
    }
}

if (!function_exists('isCompanyAdmin')) {
    /**
     * Helper para verificar se é admin da empresa
     */
    function isCompanyAdmin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return in_array(auth()->user()->user_type, ['super_admin', 'company_admin']);
    }
}