<?php

namespace App\Helpers;

class LocaleHelper
{
    /**
     * Gerar URL com locale atual
     */
    public static function route($name, $parameters = [], $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $parameters['locale'] = $locale;
        
        return route($name, $parameters);
    }
    
    /**
     * Gerar URL para trocar idioma mantendo a rota atual
     */
    public static function routeWithLocale($locale)
    {
        $currentRoute = request()->route();
        $routeName = $currentRoute->getName();
        $routeParams = $currentRoute->parameters();
        
        // Atualizar o locale nos parâmetros
        $routeParams['locale'] = $locale;
        
        return route($routeName, $routeParams);
    }
    
    /**
     * Obter todas as URLs da página atual em diferentes idiomas
     */
    public static function getAlternateUrls()
    {
        $alternates = [];
        $availableLocales = config('app.available_locales');
        
        foreach ($availableLocales as $locale) {
            $alternates[$locale] = self::routeWithLocale($locale);
        }
        
        return $alternates;
    }
}