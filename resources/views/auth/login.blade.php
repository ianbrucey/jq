<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side - Login content -->
        <div class="lg:flex-1 bg-gradient-to-br from-indigo-600 to-blue-500 p-6 lg:p-8 flex items-center justify-center">
            <div class="max-w-xl text-base-100 text-center lg:text-left px-4">
                <h1 class="text-3xl lg:text-5xl font-bold mb-4 lg:mb-6">Welcome Back to Justice Quest</h1>
                <p class="text-lg lg:text-xl mb-6 lg:mb-8">Log in to continue managing your legal documents efficiently.</p>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="flex-1 bg-base-100 p-6 lg:p-8 flex items-center justify-center">
            <div class="w-full max-w-md px-4">
                <x-authentication-card-logo class="mb-4" />

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        </div>

                        <div class="block">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-base-content/60 dark:text-base-content/60">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="space-y-4 mt-4">
                        @if (Route::has('password.request'))
                            <a class="block w-full text-center underline text-sm text-base-content/60 dark:text-base-content/60 hover:text-base-content dark:hover:text-base-content/90 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-button class="w-full lg:w-auto">
                            {{ __('Log in') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
