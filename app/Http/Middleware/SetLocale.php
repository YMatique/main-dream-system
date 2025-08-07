<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  if (Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }

        // 1. Pegar o locale da URL
        $locale = $request->segment(1);
        
        // 2. Verificar se é um locale válido
        $availableLocales = config('app.available_locales');
        
        if (in_array($locale, $availableLocales)) {
            // Se é um locale válido, usar ele
            App::setLocale($locale);
            session(['locale' => $locale]);
        } else {
            // Se não é um locale válido, usar o padrão ou da sessão
            $defaultLocale = session('locale', config('app.locale'));
            App::setLocale($defaultLocale);
            
            // Redirecionar para incluir o prefixo se necessário
            if (!in_array($request->segment(1), $availableLocales) && $request->path() !== '/') {
                return redirect("/{$defaultLocale}/" . $request->path());
            }
        }
        return $next($request);
    }
}
