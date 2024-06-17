@extends('master')
@section('title', 'Home')

@section('content')
    <div class="container" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="card card-box border">
            <div class="card-body">
                <h5 class="card-title fw-bold text-uppercase">Account Info</h5>

            </div>
        </div>


        <div class="card card-box">
            <div class="card-body">
                <h5 class="card-title fw-bold text-uppercase">Orders</h5>
                <div ng-if="list.length" class="table-responsive">
                    <div ng-repeat="o in list">

                    </div>
                </div>
                @include('layouts.loader')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            ngApp = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        ngApp.controller('ngCtrl', function($scope) {
            $scope.fn = NgFunctions;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.submitting = false;
            $scope.list = [];
            $scope.lastId = 0;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.lastId = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                $.post("/orders/load", {
                    lastId: $scope.lastId,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.lastId = data[ln - 1].order_id;
                        }
                    });
                }, 'json');
            }

            // $scope.load();
            scope = $scope;
        });

        const validateEmail = email => {
            return String(email).toLowerCase().match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };
    </script>
@endsection
