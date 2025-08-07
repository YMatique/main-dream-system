<div class="language-switcher">
    <div class="dropdown">
        <button class="dropdown-toggle" type="button" data-toggle="dropdown">
            <img src="{{ asset('images/flags/' . $availableLocales[$currentLocale]['flag'] . '.png') }}" alt="">
            {{ $availableLocales[$currentLocale]['name'] }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($availableLocales as $locale => $data)
                <li>
                    <a href="#" wire:click.prevent="switchLanguage('{{ $locale }}')">
                        <img src="{{ asset('images/flags/' . $data['flag'] . '.png') }}" alt="">
                        {{ $data['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>