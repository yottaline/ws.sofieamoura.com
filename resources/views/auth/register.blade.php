<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>REGISTER | Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />

    <link rel="stylesheet" href="/assets/css/style.css?v=1.0.0">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular-sanitize.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.options.positionClass = "toast-bottom-left";
        toastr.options.timeOut = 5000;
        toastr.options.preventDuplicates = true;
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #wrapper {
            padding-top: 150px;
        }

        .form-container {
            max-width: 400px;
            margin: auto;
        }

        * {
            box-sizing: border-box;
        }

        .store {
            border: 1px solid #eee;
            box-shadow: 2px 3px 2px #d9cece;
            border-radius: 10px;
            padding: 50px;
            width: 600px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div id=" wrapper">
        <div class="container" data-ng-app="myApp" data-ng-controller="myCtrl">
            <div class="mt-5 store">
                <h2 class="mb-3 text-center text-secondary">Create a new account</h2>
                <form id="retailerForm" method="post" action="register">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="retailerName">
                                    Full Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name" required id="retailerName" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="phone">
                                    Phone <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="phone" required id="phone" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="password">
                                    Password <b class="text-danger">&ast;</b></label>
                                <input type="password" class="form-control" name="password" required id="password" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="email">
                                    Email <b class="text-danger">&ast;</b></label>
                                <input type="email" class="form-control" name="email" required id="email" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="company">
                                    Company Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="company" required id="company" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="country">
                                    Country <b class="text-danger">&ast;</b></label>
                                <select name="country" id="country" class="form-select" required>
                                    <option value="default">-- SELECT YOUR COUNTRY --</option>
                                    <option ng-repeat="country in countries" ng-value="country.location_id"
                                        ng-bind="country.location_name"></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="province">
                                    Province <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="province" required id="province" />
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="mb-3">
                                <label for="city">
                                    City <b class="text-danger">&ast;</b></label></label>
                                <input type="text" class="form-control" name="city" id="city" required />
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="mb-3">
                                <label for="zipCode">
                                    city zip code</label>
                                <input type="text" class="form-control" name="zip" id="zipCode" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="address">
                                    Address</label>
                                <input type="text" class="form-control" name="address" id="address" required />
                            </div>
                        </div>

                    </div>

                    <div class="d-flex">
                        <button type="submit" class="btn btn-outline-primary">CREATE</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        var scope,
            app = angular.module('myApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('myCtrl', function($scope) {
            $scope.countries = [];
            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.dataLoader = function(reload = false) {
                var request = {
                    _token: '{{ csrf_token() }}'
                };
                $.post("/locations/load/", request, function(data) {
                    $scope.$apply(() => {
                        $scope.countries = data;
                    });
                }, 'json');
            }
            $scope.dataLoader();
            scope = $scope;
        });

        $(function() {
            $('#retailerForm').on('submit', e => e.preventDefault()).validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        digits: true,
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(form),
                        action = $(form).attr('action'),
                        method = $(form).attr('method');
                    $(form).find('button').prop('disabled', true);

                    $.ajax({
                        url: action,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                    }).done(function(data, textStatus, jqXHR) {
                        if (data.status) {
                            toastr.success(
                                "Your account has been created successfully. The account will be activated after 3 minutes"
                            );
                            setTimeout(location.replace("./login"), 300000)
                        } else toastr.error(data.message);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        toastr.error(jqXHR.responseJSON.message);
                    }).always(function() {
                        $(form).find('button').prop('disabled', false);
                    });
                }
            });

        });
    </script>
</body>

</html>
