{{-- resources/views/auth/portal-access-denied.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Negado - Portal do Funcionário</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                {{-- Icon --}}
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                
                {{-- Title --}}
                <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">
                    Acesso Negado
                </h1>
                
                {{-- Message --}}
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Você não tem permissão para acessar o Portal do Funcionário.
                </p>
                
                {{-- Reason --}}
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>Possíveis motivos:</strong><br>
                        • Seu email não está cadastrado como funcionário<br>
                        • Sua conta de funcionário está inativa<br>
                        • Você não pertence a nenhuma empresa
                    </p>
                </div>
                
                {{-- Actions --}}
                <div class="mt-6 space-y-3">
                    <a href="{{ route('portal.login') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        Tentar Novamente
                    </a>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Precisa de ajuda? 
                        <a href="mailto:suporte@empresa.com" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium">
                            Entre em contato com o administrador
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>