@extends('master')
@section('title', 'Orders History')

@section('content')
    <div class="container" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                @include('profile.side_bar')
            </div>
            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-uppercase">Orders History</h5>
                        <div ng-if="list.length" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center small">
                                        <th width="80">code</th>
                                        <th width="60">season</th>
                                        <th width="120">total</th>
                                        <th>status</th>
                                        <th width="120">created</th>
                                        <th width="120">placed</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="o in list" class="text-center small">
                                        <td class="font-monospace" ng-bind="o.order_code"></td>
                                        <td ng-bind="o.season_code"></td>
                                        <td ng-bind="fn.sepNumber(o.order_total)"></td>
                                        <td ng-bind="o.order_status"></td>
                                        <td ng-bind="fn.slice(o.order_created, 0, 10)"></td>
                                        <td ng-bind="o.order_placed ? fn.slice(o.order_placed, 0, 10) : '-'"></td>
                                        <td class="col-fit">
                                            <a href="/orders/view"
                                                class="btn btn-outline-dark btn-circle bi bi-link-45deg"></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @include('layouts.loader')
                    </div>
                </div>
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
    </script>
@endsection
