<nav x-data="{ open: false }" class="border-b bg-base-100 dark:bg-neutral-focus border-base-content/10 dark:border-base-content/70 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block w-auto h-9" style="height: 3.5rem !important;" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('navigation.dashboard') }}
                    </x-nav-link>
                    <livewire:notifications.notifications-list />
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Theme Selector -->
                <div class="relative flex items-center">
{{--                    <span class="mr-2 text-sm text-base-content/60">{{ __('navigation.theme') }}:</span>--}}
                    <select
                        class="w-40 rounded-lg select select-bordered text-base-content focus:outline-none"
                        x-model="$store.theme.current"
                        @change="$store.theme.setTheme($event.target.value)"
                    >
                        <option value="light" class="flex items-center gap-2">🌝 Light</option>
                        <option value="dark" class="flex items-center gap-2">🌚 Dark</option>
                        <option value="cupcake" class="flex items-center gap-2">🧁 Cupcake</option>
                        <option value="bumblebee" class="flex items-center gap-2">🐝 Bumblebee</option>
                        <option value="emerald" class="flex items-center gap-2">✳️ Emerald</option>
                        <option value="corporate" class="flex items-center gap-2">🏢 Corporate</option>
                        <option value="synthwave" class="flex items-center gap-2">🌃 Synthwave</option>
                        <option value="retro" class="flex items-center gap-2">👾 Retro</option>
                        <option value="cyberpunk" class="flex items-center gap-2">🤖 Cyberpunk</option>
                        <option value="valentine" class="flex items-center gap-2">🌸 Valentine</option>
                        <option value="halloween" class="flex items-center gap-2">🎃 Halloween</option>
                        <option value="garden" class="flex items-center gap-2">🌷 Garden</option>
                        <option value="forest" class="flex items-center gap-2">🌲 Forest</option>
                        <option value="aqua" class="flex items-center gap-2">💧 Aqua</option>
                        <option value="lofi" class="flex items-center gap-2">📻 Lo-Fi</option>
                        <option value="pastel" class="flex items-center gap-2">🎨 Pastel</option>
                        <option value="fantasy" class="flex items-center gap-2">🧚 Fantasy</option>
                        <option value="wireframe" class="flex items-center gap-2">📱 Wireframe</option>
                        <option value="black" class="flex items-center gap-2">⚫ Black</option>
                        <option value="luxury" class="flex items-center gap-2">💎 Luxury</option>
                        <option value="dracula" class="flex items-center gap-2">🧛 Dracula</option>
                        <option value="cmyk" class="flex items-center gap-2">🖨️ CMYK</option>
                        <option value="autumn" class="flex items-center gap-2">🍂 Autumn</option>
                        <option value="business" class="flex items-center gap-2">💼 Business</option>
                        <option value="acid" class="flex items-center gap-2">🧪 Acid</option>
                        <option value="lemonade" class="flex items-center gap-2">🍋 Lemonade</option>
                        <option value="night" class="flex items-center gap-2">🌙 Night</option>
                        <option value="coffee" class="flex items-center gap-2">☕ Coffee</option>
                    </select>
                </div>

                <!-- Add this somewhere visible in your navigation menu for debugging -->
{{--                <div class="text-xs">--}}
{{--                    Current Language: {{ App::getLocale() }}--}}
{{--                </div>--}}

                <!-- Language Selector -->
                <div class="relative flex items-center ml-4">
{{--                    <span class="mr-2 text-sm text-base-content/60">{{ __('navigation.language') }}:</span>--}}
                    <livewire:language-selector />
                </div>

                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative ms-3">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out border border-transparent rounded-md text-base-content/50 dark:text-base-content/60 bg-base-100 dark:bg-neutral-focus hover:text-base-content/70 dark:hover:text-base-content/70 focus:outline-none focus:bg-base-200 dark:focus:bg-neutral-focus active:bg-base-200 dark:active:bg-neutral-focus">
                                        {{ Auth::user()->currentTeam?->name ?? 'Personal Account' }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-base-content/60">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ Auth::check() && Auth::user()->currentTeam ? route('teams.show', Auth::user()->currentTeam->id) : '#' }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-base-content/20 dark:border-base-content/60"></div>

                                        <div class="block px-4 py-2 text-xs text-base-content/60">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="relative ms-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-base-content/30">
                                    <img class="object-cover rounded-full size-8" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out border border-transparent rounded-md text-base-content/50 dark:text-base-content/60 bg-base-100 dark:bg-neutral-focus hover:text-base-content/70 dark:hover:text-base-content/70 focus:outline-none focus:bg-base-200 dark:focus:bg-neutral-focus active:bg-base-200 dark:active:bg-neutral-focus">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-base-content/60">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            @can('manage-project-tokens')
                                <x-dropdown-link href="{{ route('openai.projects.index') }}">
                                    {{ __('navigation.manage_project_tokens') }}
                                </x-dropdown-link>
                            @endcan
                            <div class="border-t border-base-content/20 dark:border-base-content/60"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 transition duration-150 ease-in-out rounded-md text-base-content/60 dark:text-base-content/50 hover:text-base-content/50 dark:hover:text-base-content/60 hover:bg-base-200 dark:hover:bg-neutral-focus focus:outline-none focus:bg-base-200 dark:focus:bg-neutral-focus focus:text-base-content/50 dark:focus:text-base-content/60">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('navigation.dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('address-book.index') }}" :active="request()->routeIs('address-book.*')">
                {{ __('navigation.address_book') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-base-content/20 dark:border-base-content/60">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="object-cover rounded-full size-10" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="text-base font-medium text-base-content/80 dark:text-base-content/80">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-base-content/50">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-base-content/20 dark:border-base-content/60"></div>

                    <div class="block px-4 py-2 text-xs text-base-content/60">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ Auth::user()->currentTeam ? route('teams.show', Auth::user()->currentTeam->id) : '#' }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-base-content/20 dark:border-base-content/60"></div>

                        <div class="block px-4 py-2 text-xs text-base-content/60">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
