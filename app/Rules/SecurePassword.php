<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SecurePassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $config = config('security.password');
        
        // Verificar comprimento mínimo
        if (strlen($value) < $config['min_length']) {
            $fail("A senha deve ter pelo menos {$config['min_length']} caracteres.");
            return;
        }
        
        // Verificar maiúsculas
        if ($config['require_uppercase'] && !preg_match('/[A-Z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra maiúscula.');
            return;
        }
        
        // Verificar minúsculas
        if ($config['require_lowercase'] && !preg_match('/[a-z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra minúscula.');
            return;
        }
        
        // Verificar números
        if ($config['require_numbers'] && !preg_match('/[0-9]/', $value)) {
            $fail('A senha deve conter pelo menos um número.');
            return;
        }
        
        // Verificar símbolos
        if ($config['require_symbols'] && !preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('A senha deve conter pelo menos um símbolo especial.');
            return;
        }
        
        // Verificar senhas comuns
        $commonPasswords = [
            '123456', 'password', '123456789', '12345678', '12345',
            '1234567', '1234567890', 'qwerty', 'abc123', 'Password',
            'password123', 'admin', 'administrator'
        ];
        
        if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
            $fail('Esta senha é muito comum. Escolha uma senha mais segura.');
            return;
        }
        
        // Verificar se não é sequencial
        if (preg_match('/(?:012|123|234|345|456|567|678|789|890|abc|bcd|cde|def)/i', $value)) {
            $fail('A senha não deve conter sequências óbvias.');
            return;
        }
        
        // Verificar repetições
        if (preg_match('/(.)\1{2,}/', $value)) {
            $fail('A senha não deve conter mais de 2 caracteres iguais consecutivos.');
            return;
        }
    }
}
