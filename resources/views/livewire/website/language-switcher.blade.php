<!-- resources/views/livewire/language-switcher.blade.php -->
<div class="language-switcher">
    <div class="dropdown">
        <button class="dropdown-toggle btn" type="button" data-toggle="dropdown">
            {{ $availableLocales[$currentLocale]['flag'] }}
            {{ $availableLocales[$currentLocale]['name'] }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($availableLocales as $locale => $data)
                @if($locale !== $currentLocale)
                    <li>
                        <a href="#" wire:click.prevent="switchLanguage('{{ $locale }}')">
                            {{ $data['flag'] }} {{ $data['name'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>