<?php

namespace App\Livewire\Website;

use App\Helpers\LocaleHelper;
use Livewire\Component;

class LanguageSwitcher extends Component
{
      public $currentLocale;
    public $availableLocales;
    public $alternateUrls;

    public function mount()
    {
        $this->currentLocale = app()->getLocale();
        $this->availableLocales = [
               'pt' => ['name' => 'Português', 'flag' => '🇲🇿'],
            'en' => ['name' => 'English', 'flag' => '🇺🇸'],
            'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        ];
              // Gerar URLs alternativas para a página atual
        $this->alternateUrls = LocaleHelper::getAlternateUrls();
    }

    public function switchLanguage($locale)
    {
        return redirect($this->alternateUrls[$locale] ?? "/{$locale}");
    }
    public function render()
    {
        return view('livewire.website.language-switcher');
    }
}
