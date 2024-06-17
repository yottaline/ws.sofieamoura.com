<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="container" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <h3 class="text-center my-5">
            <img src="/assets/img/logo.png" alt="Sofie Amoura" draggable="false" width="200">
        </h3>

        <div class="row">
            <div class="col-12 col-sm-6">
                <h5 class="text-center">Login</h5>
                <div class="card border-0 m-auto">
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
                                <input id="login-password" name="password" type="password"
                                    class="form-control form-control-sm" required>
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
            <div class="col-12 col-sm-6">
                <h5 class="text-center">Register</h5>
                <div class="card border-0 m-auto">
                    <div class="card-body">
                        <form id="registerForm" method="post" action="register">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="retailerName">Full Name<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control form-control-sm" name="name"
                                            id="retailerName" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="company">Company<b class="text-danger">&ast;</b></label>
                                        <input id="company" type="text" class="form-control form-control-sm"
                                            name="company" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Email<b class="text-danger">&ast;</b></label>
                                        <input id="email" type="email" class="form-control form-control-sm"
                                            name="email" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="tel" class="form-control form-control-sm" name="phone"
                                            id="phone">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="country">Country<b class="text-danger">&ast;</b></label>
                                        <select name="country" id="country" class="form-select form-select-sm"
                                            required>
                                            <option value="default"></option>
                                            <option ng-repeat="c in countries" ng-value="c.location_id"
                                                ng-bind="c.location_name"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="province">Province</label>
                                        <input id="province" type="text" class="form-control form-control-sm"
                                            name="province">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="city">City<b class="text-danger">&ast;</b></label></label>
                                        <input type="text" class="form-control form-control-sm" name="city"
                                            id="city" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="zipCode">Zip code</label>
                                        <input type="text" class="form-control form-control-sm" name="zip"
                                            id="zipCode">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="address">Address</label>
                                        <textarea type="text" class="form-control form-control-sm" name="address" id="address" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-outline-dark rounded-pill px-5"
                                    ng-disabled="register">
                                    <span ng-if="register" class="spinner-border spinner-border-sm me-2"
                                        role="status"></span>Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var scope,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.countries = [];
            $scope.register = false;

            $scope.loadCountries = (reload = false) => {
                $.post("/locations/load", {
                    _token: '{{ csrf_token() }}'
                }, data => $scope.$apply(() => $scope.countries = data), 'json');
            }

            $scope.loadCountries();
            scope = $scope;
        });

        $(function() {
            $('#loginForm').validate();

            $('#registerForm').on('submit', e => e.preventDefault()).validate({
                rules: {
                    phone: {
                        digits: true,
                    },
                    zip: {
                        digits: true,
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(form),
                        action = $(form).attr('action'),
                        method = $(form).attr('method');

                    scope.$apply(() => scope.register = true);
                    $.ajax({
                        url: action,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                    }).done(function(data, textStatus, jqXHR) {
                        if (data.status) location.replace('/account/request');
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        toastr.error(jqXHR.responseJSON.message);
                    }).always(function() {
                        scope.$apply(() => scope.register = false);
                    });
                }
            });
        });
    </script>
</x-guest-layout>
