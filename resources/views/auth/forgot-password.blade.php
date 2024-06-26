<x-guest-layout>
    <div class="container">
        <div class="card m-auto mt-5" style="max-width: 350px">
            <div class="card-body">
                <div class="mb-4 text-sm text-gray-600">
                    <b>Forgot your password? No problem</b><br>
                    <small>Enter your email address and we will email you a password reset link</small>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="post" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control mt-1" type="email" name="email"
                            :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="btn btn-outline-dark px-4 rounded-pill">{{ __('Email a Reset Link') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
