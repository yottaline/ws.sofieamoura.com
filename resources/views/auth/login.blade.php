<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="container" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <h3 class="text-center my-5">
            <img src="/assets/img/logo.png" alt="Sofie Amoura" draggable="false" width="200">
        </h3>

        <h5 class="text-center">Login</h5>
        <div class="card border-0 m-auto" style="max-width: 400px">
            <div class="card-body">
                @if ($errors->any())
                    <div class="text-danger pb-3">
                        @foreach ($errors->all() as $error)
                            <small class="d-block">{{ $error }}</small>
                        @endforeach
                    </div>
                @endif
                <form id="loginForm" action="" method="post" role="form">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="login-email">Email<b class="text-danger">&ast;</b></label>
                        <input id="login-email" name="email" type="email" value="{{ old('email') }}"
                            class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="login-password">Password<b class="text-danger">&ast;</b></label>
                        <input id="login-password" name="password" type="password" class="form-control form-control-sm"
                            required>
                    </div>
                    @if (Route::has('password.request'))
                        <small class="d-block my-3"><i class="bi bi-lock text-muted"></i> <a
                                href="{{ route('password.request') }}" class="link-dark" target="_self">Forgot
                                your password?</a></small>
                    @endif
                    <input type="hidden" name="token" value="0">
                    <input type="hidden" name="action" value="login_form">
                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-dark rounded-pill px-5">Login</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $(function() {
            $('#loginForm').validate();
        });
    </script>
</x-guest-layout>
