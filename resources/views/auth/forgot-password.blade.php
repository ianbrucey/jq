<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side - Forgot Password content -->
        <div class="lg:flex-1 bg-gradient-to-br from-indigo-600 to-blue-500 p-6 lg:p-8 flex items-center justify-center">
            <div class="max-w-xl text-base-100 text-center lg:text-left px-4">
                <h1 class="text-3xl lg:text-5xl font-bold mb-4 lg:mb-6">Reset Your Password</h1>
                <p class="text-lg lg:text-xl mb-6 lg:mb-8">Enter your email to receive a password reset link.</p>
            </div>
        </div>

        <!-- Right side - Forgot Password form -->
        <div class="flex-1 bg-base-100 p-6 lg:p-8 flex items-center justify-center">
            <div class="w-full max-w-md px-4">
                <x-authentication-card-logo class="mb-4" />

                <div class="mb-4 text-sm lg:text-base text-base-content/60 dark:text-base-content/60">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div class="block">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="w-full">
                        <x-button class="w-full lg:w-auto">
                            {{ __('Email Password Reset Link') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
