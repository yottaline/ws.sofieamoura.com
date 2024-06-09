@extends('index')
@section('title', 'settings')
@section('content')
    <div class="container-fluid" data-ng-app="ngApp" data-ng-controller="ngCtrl">

        {{-- start compa section  --}}
        <div class="card card-box">
            <div class="card-body">
                <div class="row">
                    <div id="locations" class="col-6">
                        <div class="list-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>AVAILABLE COUNTRIES</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                        data-ng-click="load(true)"></button>
                                </div>

                            </div>
                            <div data-ng-if="locations.length" class="table-responsive">
                                <table class="table table-hover" id="location-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Country Name</th>
                                            <th class="text-center">Country Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-ng-repeat="l in locations">
                                            <td data-ng-bind="l.location_id"></td>
                                            <td data-ng-bind="l.location_name"></td>
                                            <td class="text-center" data-ng-bind="l.location_code"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!locations.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                    </div>

                    <div id="currency" class="col-6">
                        <div class="list-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>AVAILABLE CURRENCIES</span>
                                </h5>

                            </div>
                            <div data-ng-if="currencies.length" class="table-responsive">
                                <table class="table table-hover" id="currencies_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Currency Name</th>
                                            <th class="text-center">Currency Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-ng-repeat="c in currencies">
                                            <td data-ng-bind="c.currency_id"></td>
                                            <td data-ng-bind="c.currency_name"></td>
                                            <td class="text-center" data-ng-bind="c.currency_code"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!currencies.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- end compa section  --}}

    </div>
@endsection
@section('js')
    <script>
        var scope, ngApp = angular.module("ngApp", ['ngSanitize'], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        });
        ngApp.controller("ngCtrl", function($scope) {
            $('.loading-spinner').hide();
            // locations
            $scope.updateLocation = false;
            $scope.locations = [];

            $scope.currencies = <?= json_encode($currencies) ?>;
            $scope.jsonParse = str => JSON.parse(str);

            $scope.load = function(reload = false) {
                $scope.loading = true;

                $('.loading-spinner').show();
                var request = {
                    _token: '{{ csrf_token() }}'
                };

                $.post("/locations/load", request, function(data) {
                    $('.loading-spinner').hide();
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.locations = data;
                        console.log(data)
                    });
                }, 'json');
            }
            $scope.load();
            scope = $scope;
        });
    </script>
@endsection
