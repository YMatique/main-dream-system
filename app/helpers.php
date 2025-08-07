<?php

if (!function_exists('locale_route')) {
    /**
     * Gerar URL com locale atual
     */
    function locale_route($name, $parameters = [], $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $parameters['locale'] = $locale;
        
        return route($name, $parameters);
    }
}

if (!function_exists('alternate_urls')) {
    /**
     * Obter URLs alternativas para a página atual
     */
    function alternate_urls()
    {
        $alternates = [];
        $availableLocales = config('app.available_locales');
        $currentRoute = request()->route();
        
        if (!$currentRoute) {
            return $alternates;
        }
        
        $routeName = $currentRoute->getName();
        $routeParams = $currentRoute->parameters();
        
        foreach ($availableLocales as $locale) {
            $routeParams['locale'] = $locale;
            try {
                $alternates[$locale] = route($routeName, $routeParams);
            } catch (\Exception $e) {
                $alternates[$locale] = "/{$locale}";
            }
        }
        
        return $alternates;
    }
}

if (!function_exists('current_locale')) {
    /**
     * Obter locale atual
     */
    function current_locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_current_locale')) {
    /**
     * Verificar se é o locale atual
     */
    function is_current_locale($locale)
    {
        return app()->getLocale() === $locale;
    }
}