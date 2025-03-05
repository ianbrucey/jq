<div class="relative flex items-center">
    <select
        class="w-40 rounded-lg select select-bordered text-base-content focus:outline-none"
        x-data
        x-model="$wire.currentLanguage"
        @change="$wire.switchLanguage($event.target.value)"
    >
        @foreach($availableLanguages as $code => $language)
            <option value="{{ $code }}" class="flex items-center gap-2">
                {{ $language['flag'] }} {{ $language['native_name'] }}
            </option>
        @endforeach
    </select>
</div>
