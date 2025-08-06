<?php

// config/security.php
return [
    /*
    |--------------------------------------------------------------------------
    | Sistema de Segurança - Configurações
    |--------------------------------------------------------------------------
    */

    // Configurações de senha
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
        'max_age_days' => 90, // Forçar troca de senha
        'prevent_reuse' => 5, // Não permitir reutilizar últimas 5 senhas
    ],

    // Configurações de sessão e login
    'session' => [
        'max_concurrent_sessions' => 3, // Máximo de sessões simultâneas por usuário
        'idle_timeout_minutes' => 120, // Timeout por inatividade
        'max_login_attempts' => 5, // Tentativas de login
        'lockout_duration_minutes' => 15, // Tempo de bloqueio após tentativas
    ],

    // Configurações de auditoria
    'audit' => [
        'log_all_requests' => false, // Log todas as requisições (cuidado com performance)
        'log_failed_logins' => true,
        'log_data_exports' => true,
        'sensitive_fields' => [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'two_factor_secret',
        ],
    ],

    // Configurações de rate limiting
    'rate_limits' => [
        'login' => [
            'attempts' => 5,
            'per_minutes' => 15,
        ],
        'api' => [
            'attempts' => 100,
            'per_minutes' => 1,
        ],
        'exports' => [
            'attempts' => 10,
            'per_minutes' => 60,
        ],
    ],

    // Configurações de IP
    'ip_restrictions' => [
        'enabled' => false,
        'admin_ips' => [], // IPs permitidos para super admins
        'company_ip_restrictions' => true, // Empresas podem definir IPs permitidos
    ],

    // Configurações de criptografia
    'encryption' => [
        'sensitive_model_fields' => [
            'phone',
            'tax_id',
            'bank_account',
        ],
    ],
];