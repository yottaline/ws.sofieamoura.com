@extends('index')
@section('title', 'Profile')
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-8 col-lg-3">

            </div>
            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <h3 class="text-body-tertiary">#<% retailer[0].retailer_code %></h3>
                        <hr>
                        {{-- start form data --}}
                        <form method="POST" id="wProductF" action="/update">
                            @csrf
                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="product_id" ng-value="retailer.retailer_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="name">
                                                    Name <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="name"
                                                    ng-value="retailer[0].retailer_fullName" id="name" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="email">
                                                    Email <b class="text-danger">&ast;</b></label>
                                                <input type="email" class="form-control" name="email"
                                                    ng-value="retailer[0].retailer_email" id="email" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="phone">
                                                    Phone <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="phone"
                                                    ng-value="retailer[0].retailer_phone" id="phone" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="password">
                                                    Password <b class="text-danger">&ast;</b></label>
                                                <input type="password" class="form-control" name="password"
                                                    id="password" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="company">
                                                    Company Name <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="company"
                                                    ng-value="retailer[0].retailer_company" id="company" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="website">
                                                    Company Website <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="website"
                                                    ng-value="retailer[0].retailer_website" id="website" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="logo">
                                                    Company Logo <b class="text-danger">&ast;</b></label>
                                                <input type="file" class="form-control" name="logo" id="logo" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="payment">
                                                    Advance payment <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="payment"
                                                    ng-value="retailer[0].retailer_adv_payment" id="payment" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="desc">
                                                    Descaption <b class="text-danger">&ast;</b></label>
                                                <textarea name="desc" class="form-control" id="desc" cols="30" rows="10"><%retailer[0].retailer_desc%></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="currency">
                                                    currency <b class="text-danger">&ast;</b></label>
                                                <select name="currency" id="currency" class="form-select" required>
                                                    <option value="default">-- SELECT YOUR CURRENCY --</option>
                                                    <option ng-repeat="currency in currencies"
                                                        ng-value="currency.currency_id" ng-bind="currency.currency_name">
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="country">
                                                    country <b class="text-danger">&ast;</b></label>
                                                <select name="country" id="country" class="form-select" required>
                                                    <option value="default">-- SELECT YOUR COUNTRY --</option>
                                                    <option ng-repeat="country in countries"
                                                        ng-value="country.location_id" ng-bind="country.location_name">
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="province">
                                                    Province <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="province"
                                                    ng-value="retailer[0].retailer_province" id="province" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="zipCode">
                                                    City zip code <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="zip"
                                                    ng-value="retailer[0].address_zip" id="zipCode" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="city">City <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="city"
                                                    ng-value="retailer[0].retailer_city" id="city" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="shippingAddress">Shipping Address <b
                                                        class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="shipping"
                                                    ng-value="retailer[0].address_line1" id="shippingAddress" />
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="billAddress">Bill Address <b
                                                        class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="bill"
                                                    ng-value="retailer[0].address_line2" id="billAddress" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="address">Address <b class="text-danger">&ast;</b></label>
                                                <input type="text" class="form-control" name="address"
                                                    ng-value="retailer[0].retailer_address" id="address" />
                                            </div>
                                        </div>

                                        {{--
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="streetN">
                                                    Street number or street name</label>
                                                <input type="text" class="form-control" name="line1"
                                                    ng-value="retailer[0].address_line1" id="streetN" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="Anumber">
                                                    Apartment number </label>
                                                <input type="text" class="form-control" name="line2"
                                                    ng-value="retailer[0].address_line2" id="Anumber" />
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="d-flex mt-2">
                                    <button type="submit"
                                        class="btn btn-outline-primary text-end me-auto">Update</button>
                                </div>
                        </form>
                        <script>
                            $('#wProductF').on('submit', e => e.preventDefault()).validate({
                                rules: {
                                    name: {
                                        required: true
                                    },
                                    reference: {
                                        required: true
                                    },
                                    season: {
                                        required: true
                                    },
                                    category: {
                                        required: true
                                    },
                                    description: {
                                        required: true
                                    },
                                    order_type: {
                                        required: true
                                    },
                                    minqty: {
                                        required: true,
                                        digits: true,
                                    },
                                    maxqty: {
                                        digits: true,
                                        required: true
                                    },
                                    minorder: {
                                        digits: true,
                                    },
                                    discount: {
                                        digits: true,
                                        max: 100
                                    },
                                    delivery: {
                                        required: true
                                    },
                                    related: {
                                        required: true
                                    }
                                },
                                submitHandler: function(form) {
                                    console.log(form);
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
                                    }).done(function(data, textStatus, jqXHR) {
                                        var response = JSON.parse(data);
                                        if (response.status) {
                                            toastr.success('Data processed successfully');
                                            scope.$apply(() => {
                                                scope.retailer.unshift(response
                                                    .data);
                                            });
                                        } else toastr.error(response.message);
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus)
                                        toastr.error("error");
                                    }).always(function() {
                                        $(form).find('button').prop('disabled', false);
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });
        app.controller('ngCtrl', function($scope) {
            $scope.jsonParse = (string) => JSON.parse(string);
            $scope.retailer = <?= json_encode($user) ?>;
            $scope.countries = <?= json_encode($countries) ?>;
            $scope.currencies = <?= json_encode($currencies) ?>;


            scope = $scope;
        });
    </script>
@endsection