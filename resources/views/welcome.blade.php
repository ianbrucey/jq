<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side - Landing content -->
        <div class="lg:flex-1 bg-gradient-to-br from-indigo-600 to-blue-500 p-6 lg:p-8 flex items-center justify-center">
            <div class="max-w-xl text-base-100 text-center lg:text-left px-4">
                <h1 class="text-3xl lg:text-5xl font-bold mb-4 lg:mb-6">Justice Quest</h1>
                <p class="text-lg lg:text-xl mb-6 lg:mb-8">Your AI-powered legal document assistant that streamlines case management, automates document creation, and enhances your legal practice efficiency.</p>
                <div class="space-y-4 text-left">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Intelligent document management</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Smart case organization</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Automated reminders</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="flex-1 bg-base-100 p-8 flex items-center justify-center">
            <div class="w-full max-w-md">
                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
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
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-base-content/60">{{ __('Remember me') }}</span>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="block text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <x-button class="w-full justify-center">
                            {{ __('Log in') }}
                        </x-button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-sm text-base-content/60">
                            {{ __('Don\'t have an account?') }}
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                {{ __('Register now') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
