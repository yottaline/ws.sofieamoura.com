@extends('master')
@section('title', 'Account Info')

@section('content')
    <div class="container-fluid" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                @include('profile.side_bar')
            </div>
            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-uppercase mb-4">
                            <span class="me-2">Account</span>
                            <span class="font-monospace text-secondary fw-light small">#{{ $info->retailer_code }}</span>
                        </h5>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <strong class="text-secondary">Company Name</strong>
                                    <p class="fw-bold">{{ $info->retailer_company }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-secondary">Full Name</strong>
                                    <p class="fw-bold">{{ $info->retailer_fullName }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-secondary">Email Address</strong>
                                    <p class="fw-bold">{{ $info->retailer_email }}</p>
                                </div>
                                @if ($info->retailer_phone)
                                    <div class="mb-3">
                                        <strong class="text-secondary">Contact</strong>
                                        <p class="fw-bold">{{ $info->retailer_phone }}</p>
                                    </div>
                                @endif
                                <div class="mb-5">
                                    <strong class="text-secondary">Registered</strong>
                                    <p class="font-monospace small">{{ substr($info->retailer_created, 0, 10) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-4">
                                    <div class="card small">
                                        <div class="card-body">
                                            <h6 class="card-title small text-uppercase">
                                                <span ng-if="submitBillAddress"
                                                    class="spinner-border text-primary spinner-border-sm me-2"
                                                    role="status"></span>
                                                <span>Billing Address</span>
                                            </h6>

                                            @foreach ($addresses as $a)
                                                @if ($a->address_type == 1)
                                                    <form id="billingAddressForm" action="profile/update_address"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="put">
                                                        <input type="hidden" name="retailer"
                                                            value="{{ $info->retailer_code }}">
                                                        <input type="hidden" name="address" value="{{ $a->address_id }}">
                                                        <div class="row">
                                                            <di class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="billCountry">Country<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="billCountry" name="country" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_country }}" required>
                                                                </div>
                                                            </di>
                                                            <div class="col-12 col-lg-6">
                                                                {{-- <div class="mb-3">
                                                                    <label for="billProvince">Province</label>
                                                                    <input id="billProvince" name="province" type="text"
                                                                        class="form-control form-control-sm" value="{{ $a->address_province }}"
                                                                        maxlength="120">
                                                                </div> --}}
                                                                <div class="mb-3">
                                                                    <label for="billCity">City<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="billCity" name="city" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_city }}" maxlength="120"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="billZip">Zip/Postal Code</label>
                                                                    <input id="billZip" name="zip" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_zip }}" maxlength="24">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="billPhone">Contact<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="billPhone" name="phone" type="tel"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_phone }}" maxlength="24"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="billLine1">Address Line 1<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="billLine1" name="line1"
                                                                class="form-control form-control-sm" maxlength="255"
                                                                value="{{ $a->address_line1 }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="billLine2">Address Line 2</label>
                                                            <input id="billLine2" name="line2"
                                                                class="form-control form-control-sm" maxlength="255"
                                                                value="{{ $a->address_line2 }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="billNote">Note</label>
                                                            <textarea id="billNote" name="note" class="form-control form-control-sm" maxlength="1024" rows="2">{{ $a->address_note }}</textarea>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="submit"
                                                                class="btn btn-outline-dark rounded-pill px-4 btn-sm">Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(function() {
                                                            $('#billingAddressForm').on('submit', e => e.preventDefault()).validate({
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

                                                                    scope.$apply(() => scope.submitBillAddress = true);
                                                                    $.ajax({
                                                                        url: action,
                                                                        type: method,
                                                                        data: formData,
                                                                        processData: false,
                                                                        contentType: false,
                                                                        dataType: "json",
                                                                    }).done(function(data, textStatus, jqXHR) {
                                                                        scope.$apply(() => {
                                                                            scope.submitBillAddress = false;
                                                                            if (data.status) toastr.success(
                                                                                'Billing address updated successfully');
                                                                            else toastr.error(data.message);
                                                                        });
                                                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                                                        console.log(jqXHR.responseJSON.message);
                                                                        scope.$apply(() => scope.submitBillAddress = false);
                                                                    });
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-4">
                                    <div class="card small">
                                        <div class="card-body">
                                            <h6 class="card-title small text-uppercase">
                                                <span ng-if="submitShipAddress"
                                                    class="spinner-border text-primary spinner-border-sm me-2"
                                                    role="status"></span>
                                                <span>Shipping Address</span>
                                            </h6>
                                            @foreach ($addresses as $a)
                                                @if ($a->address_type == 2)
                                                    <form id="shippingAddressForm" action="profile/update_address"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="put">
                                                        <input type="hidden" name="retailer"
                                                            value="{{ $info->retailer_code }}">
                                                        <input type="hidden" name="address"
                                                            value="{{ $a->address_id }}">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="shipCountry">Country<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="shipCountry" name="country" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_country }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-6">{{-- <div class="mb-3">
                                                                <label for="shipProvince">Province</label>
                                                                <input id="shipProvince" name="province" type="text"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $a->address_province }}" maxlength="120">
                                                            </div> --}}
                                                                <div class="mb-3">
                                                                    <label for="shipCity">City<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="shipCity" name="city" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_city }}" maxlength="120"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="shipZip">Zip/Postal Code</label>
                                                                    <input id="shipZip" name="zip" type="text"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_zip }}" maxlength="24">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="shipPhone">Contact<b
                                                                            class="text-danger">&ast;</b></label>
                                                                    <input id="shipPhone" name="phone" type="tel"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ $a->address_phone }}" maxlength="24"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="shipLine1">Address Line 1<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="shipLine1" name="line1"
                                                                class="form-control form-control-sm" maxlength="255"
                                                                value="{{ $a->address_line1 }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="shipLine2">Address Line 2</label>
                                                            <input id="shipLine2" name="line2"
                                                                class="form-control form-control-sm" maxlength="255"
                                                                value="{{ $a->address_line2 }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="shipNote">Note</label>
                                                            <textarea id="shipNote" name="note" class="form-control form-control-sm" maxlength="1024" rows="2">{{ $a->address_note }}</textarea>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="submit"
                                                                class="btn btn-outline-dark rounded-pill px-4 btn-sm">Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(function() {
                                                            $('#shippingAddressForm').on('submit', e => e.preventDefault()).validate({
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

                                                                    scope.$apply(() => scope.submitShipAddress = true);
                                                                    $.ajax({
                                                                        url: action,
                                                                        type: method,
                                                                        data: formData,
                                                                        processData: false,
                                                                        contentType: false,
                                                                        dataType: "json",
                                                                    }).done(function(data, textStatus, jqXHR) {
                                                                        scope.$apply(() => {
                                                                            scope.submitShipAddress = false;
                                                                            if (data.status) toastr.success(
                                                                                'Shipping address updated successfully');
                                                                            else toastr.error(data.message);
                                                                        });
                                                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                                                        console.log(jqXHR.responseJSON.message);
                                                                        scope.$apply(() => scope.submitShipAddress = false);
                                                                    });
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            $scope.submitBillAddress = false;
            $scope.submitShipAddress = false;

            scope = $scope;
        });
    </script>
@endsection
