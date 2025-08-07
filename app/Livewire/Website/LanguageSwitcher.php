<?php

namespace App\Livewire\Website;

use Livewire\Component;

class LanguageSwitcher extends Component
{
     public $currentLocale;
    public $availableLocales;
    
    public function mount()
    {
        $this->currentLocale = app()->getLocale();
        $this->availableLocales = [
            'pt' => ['name' => 'Português', 'flag' => '🇲🇿'],
            'en' => ['name' => 'English', 'flag' => '🇺🇸'],
            'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        ];
    }
    
    public function switchLanguage($locale)
    {
        session(['locale' => $locale]);
        return redirect()->to(request()->header('Referer'));
    }
    public function render()
    {
        return view('livewire.website.language-switcher');
    }
}
