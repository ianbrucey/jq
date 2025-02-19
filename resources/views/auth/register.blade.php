<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side - Register content -->
        <div class="lg:flex-1 bg-gradient-to-br from-indigo-600 to-blue-500 p-6 lg:p-8 flex items-center justify-center">
            <div class="max-w-xl text-base-100 text-center lg:text-left px-4">
                <h1 class="text-3xl lg:text-5xl font-bold mb-4 lg:mb-6">Join Justice Quest</h1>
                <p class="text-lg lg:text-xl mb-6 lg:mb-8">Become a part of our AI-powered legal document assistant community.</p>
            </div>
        </div>

        <!-- Right side - Register form -->
        <div class="flex-1 bg-base-100 p-6 lg:p-8 flex items-center justify-center">
            <div class="w-full max-w-md px-4">
                <x-authentication-card-logo class="mb-4" />

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-start">
                                    <x-checkbox name="terms" id="terms" required class="mt-1" />

                                    <div class="ms-2 text-sm">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-base-content/60 dark:text-base-content/60 hover:text-base-content dark:hover:text-base-content/90 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-base-content/60 dark:text-base-content/60 hover:text-base-content dark:hover:text-base-content/90 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <div class="space-y-4 mt-4">
                        <a class="block w-full text-center underline text-sm text-base-content/60 dark:text-base-content/60 hover:text-base-content dark:hover:text-base-content/90 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>

                        <x-button class="w-full lg:w-auto">
                            {{ __('Register') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
