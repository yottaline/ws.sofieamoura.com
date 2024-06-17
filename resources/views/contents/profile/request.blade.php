@extends('master')
@section('title', 'Account Request')
@section('content')
    <div class="container" ng-app="ngApp" ng-controller="ngCtrl">
        <p class="my-5" style="width: 300px; mx-auto text-center">
            Request Sent...
        </p>
    </div>
@endsection
@section('js')
    <script>
        var scope, ngApp = angular.module("ngApp", ['ngSanitize'], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        });
        ngApp.controller("ngCtrl", function($scope) {
            scope = $scope;
        });
    </script>
@endsection
