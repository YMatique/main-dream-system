<!-- resources/views/livewire/language-switcher.blade.php -->
<div class="language-switcher">
    <div class="dropdown">
        <button class="dropdown-toggle btn btn-sm" type="button" data-toggle="dropdown">
            {{-- <img src="{{ asset('images/flags/' . $availableLocales[$currentLocale]['flag'] . '.png') }}" 
                 alt="{{ $availableLocales[$currentLocale]['name'] }}"
                 width="20" height="15"> --}}
                 {{ $availableLocales[$currentLocale]['flag'] }}
            {{ $availableLocales[$currentLocale]['name'] }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($availableLocales as $locale => $data)
                @if($locale !== $currentLocale)
                    <li>
                        <a href="{{ $alternateUrls[$locale] ?? "/{$locale}" }}">
                            {{-- <img src="{{ asset('images/flags/' . $data['flag'] . '.png') }}" 
                                 alt="{{ $data['name'] }}"
                                 width="20" height="15"> --}}
                                  {{ $data['flag'] }}
                            {{ $data['name'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>