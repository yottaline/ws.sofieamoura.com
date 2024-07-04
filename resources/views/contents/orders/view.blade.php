@extends('master')
@section('title', 'Order View')

@section('style')
    <style>
        .product-img {
            width: 90px;
            height: 90px;
            background-size: contain;
            background-position: center top;
            background-repeat: no-repeat;
            margin: 10px;
        }

        .qty-input {
            width: 50px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid container" ng-app="ngApp" ng-controller="ngCtrl">
        <div ng-if="fn.objectLen(parsedProducts)" class="row">
            <div class="col-12 col-lg-8">
                <div class="card card-box mb-3" ng-repeat="(pk, p) in parsedProducts">
                    <div class="card-body d-sm-flex">
                        <div class="product-img rounded mb-2"
                            style="background-image: url(<% mediaPath %>/<% p.info.product_id %>/<% p.info.media_file %>);">
                        </div>

                        <div class="flex-fill">
                            <div class="d-flex">
                                <h6 class="fw-semibold pt-1 text-uppercase small me-auto">
                                    <span class="me-1"><%p.info.product_name%></span>
                                    <span class="text-secondary">#<%p.info.product_code%></span>
                                </h6>
                                {{-- <a href="" class="link-danger h5 bi bi-x"
                                    ng-click="delProduct(p.info.product_id)"></a> --}}
                            </div>
                            <p class="text-danger small fw-bold" ng-if="p.qty < min_qty">
                                <i class="bi bi-info-circle me-1"></i><span>Minimum order qty <% min_qty %></span>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-hover sizes-table">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="text-start">Color</th>
                                            <th>Size</th>
                                            <th>WSP</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th ng-if="s.order_status == 0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="small text-center" ng-repeat="(sk, s) in p.sizes">
                                            <td ng-bind="s.prodcolor_name" class="text-uppercase text-start"></td>
                                            <td width="70" ng-bind="s.size_name">
                                            <td width="70" class="font-monospace" ng-bind="s.ordprod_price"></td>
                                            <td class="col-fit">
                                                <span ng-if="s.order_status > 0" ng-bind="s.ordprod_request_qty"></span>
                                                <input class="qty-input" ng-if="s.order_status == 0" type="text"
                                                    ng-model="s.ordprod_request_qty"
                                                    ng-blur="updateQty(s.ordprod_id, s.ordprod_request_qty)">
                                            </td>
                                            <td width="80"
                                                ng-bind="fn.toFixed(s.ordprod_request_qty * s.ordprod_price, 2)"
                                                class="text-center font-monospace"></td>
                                            <td ng-if="s.order_status == 0" class="col-fit">
                                                <a href="" class="link-danger bi bi-x"
                                                    ng-click="delSize(s.ordprod_id)"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center font-monospace small">
                                            <td colspan="3"></td>
                                            <td ng-bind="p.qty">qty</td>
                                            <td ng-bind="fn.sepNumber(p.total)">total</td>
                                            <td ng-if="s.order_status == 0"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col order-lg-first">
                <div class="card card-box">
                    <div class="card-body product-block">
                        <form id="orderForm">
                            <div class="mb-3">
                                <h6 class="fw-semibold text-uppercase">
                                    <span>ORDER</span> #<span ng-bind="order.order_code"></span>
                                </h6>
                                <h6 class="small" ng-bind="order.season_name"></h6>
                                <span class="text-primary fw-bold" ng-bind="statusObject.name[order.order_status]"></span>
                                <p class="text-secondary mb-0 small">created: <span class="font-monospace"
                                        ng-bind="fn.slice(order.order_created, 0, 16)"></span></p>
                                <p class="text-secondary mb-0 small" ng-if="order.order_status == 0 && order.order_placed">
                                    placed: <span class="font-monospace"
                                        ng-bind="fn.slice(order.order_placed, 0, 16)"></span></p>
                            </div>

                            @foreach ($addresses as $a)
                                @if ($a->address_type == 1)
                                    <div class="card mb-3">
                                        <div class="card-body small">
                                            <h6 class="card-title text-uppercase d-flex">
                                                <span class="me-auto">Billing Address</span>
                                                {{-- <a href="" data-target="#billBox"
                                                    class="address-toggle-btn bi bi-dash-square link-secondary small"
                                                    ng-if="order.order_status == 0"></a> --}}
                                            </h6>
                                            <div id="billBox" ng-if="order.order_status == 0">
                                                <input type="hidden" id="bill_address" value="{{ $a->address_id }}">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="billCountry">Country<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="billCountry" name="bill_country" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_country }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="billCity">City<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="billCity" name="bill_city" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_city }}" maxlength="120" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="billZip">Zip/Postal Code</label>
                                                            <input id="billZip" name="bill_zip" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_zip }}" maxlength="24">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="billPhone">Contact Number<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="billPhone" name="bill_phone" type="tel"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_phone }}" maxlength="24" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="billLine1">Address Line 1<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input id="billLine1" name="bill_line1" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_line1 }}" maxlength="255" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="billLine2">Address Line 2</label>
                                                    <input id="billLine2" name="bill_line2" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_line2 }}" maxlength="255">
                                                </div>
                                                <div>
                                                    <label for="billNote">Billing Note</label>
                                                    <input id="billNote" name="bill_note" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_note }}" maxlength="1024">
                                                </div>
                                            </div>
                                            <div ng-if="order.order_status >= 2" class="small fw-bold">
                                                <p class="m-0">{{ $a->address_line2 }}</p>
                                                <p class="m-0">{{ $a->address_line1 }}</p>
                                                <p class="m-0">{{ $a->address_country }},{{ $a->address_city }}</p>
                                                <p class="m-0">Postal/Zip Code: {{ $a->address_zip }}</p>
                                                <p class="m-0">Contact Number: {{ $a->address_phone }}</p>
                                                <p class="m-0">Billing Note:<br>{{ $a->address_note }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($a->address_type == 2)
                                    <div class="card mb-3">
                                        <div class="card-body small">
                                            <h6 class="card-title text-uppercase d-flex">
                                                <span class="me-auto">Shipping Address</span>
                                                {{-- <a href="" data-target="#shipBox"
                                                    class="address-toggle-btn bi bi-dash-square link-secondary small"
                                                    ng-if="order.order_status == 0"></a> --}}
                                            </h6>
                                            <div id="shipBox" ng-if="order.order_status == 0">
                                                <input type="hidden" id="ship_address" value="{{ $a->address_id }}">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="shipCountry">Country<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="shipCountry" name="ship_country" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_country }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="shipCity">City<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="shipCity" name="ship_city" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_city }}" maxlength="120" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="shipZip">Zip/Postal Code</label>
                                                            <input id="shipZip" name="ship_zip" type="text"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_zip }}" maxlength="24">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="shipPhone">Contact Number<b
                                                                    class="text-danger">&ast;</b></label>
                                                            <input id="shipPhone" name="ship_phone" type="tel"
                                                                class="address-input form-control form-control-sm"
                                                                value="{{ $a->address_phone }}" maxlength="24" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="shipLine1">Address Line 1<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input id="shipLine1" name="ship_line1" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_line1 }}" maxlength="255" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="shipLine2">Address Line 2</label>
                                                    <input id="shipLine2" name="ship_line2" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_line2 }}" maxlength="255">
                                                </div>
                                                <div>
                                                    <label for="shipNote">Shipping Note</label>
                                                    <input id="shipNote" name="ship_note" type="text"
                                                        class="address-input form-control form-control-sm"
                                                        value="{{ $a->address_note }}" maxlength="1024">
                                                </div>
                                            </div>
                                            <div ng-if="order.order_status >= 2" class="small fw-bold">
                                                <p class="m-0">{{ $a->address_line1 }}</p>
                                                <p class="m-0">{{ $a->address_line2 }}</p>
                                                <p class="m-0">{{ $a->address_country }},{{ $a->address_city }}</p>
                                                <p class="m-0">Postal/Zip Code: {{ $a->address_zip }}</p>
                                                <p class="m-0">Contact Number: {{ $a->address_phone }}</p>
                                                <p class="m-0">Billing Note:<br>{{ $a->address_note }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <table class="table small">
                                <tbody>
                                    <tr ng-if="+order.order_discount">
                                        <td class="col-fit">Subtotal</td>
                                        <td class="text-end font-monospace">
                                            <span ng-bind="order.currency_code"></span>
                                            <span ng-bind="fn.sepNumber(order.order_subtotal)"></span>
                                        </td>
                                    </tr>
                                    <tr ng-if="+order.order_discount">
                                        <td class="col-fit">Discount</td>
                                        <td class="text-end font-monospace">
                                            <span ng-bind="order.currency_code"></span>
                                            <span ng-bind="fn.sepNumber(order.order_discount)"></span>
                                        </td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td class="col-fit">Total</td>
                                        <td class="text-end font-monospace">
                                            <span ng-bind="order.currency_code"></span>
                                            <span ng-bind="fn.sepNumber(order.order_total)"></span>
                                        </td>
                                    </tr>
                                    <tr class="text-secondary">
                                        <td class="col-fit">Adv. Payment 30%</td>
                                        <td class="text-end font-monospace">
                                            <span ng-bind="order.currency_code"></span>
                                            <span ng-bind="fn.sepNumber(order.order_total * 0.3)"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h6 class="font-monospace small">Qty: <span ng-bind="orderQty"></span></h6>

                            <p class="text-danger small fw-bold" ng-if="order.order_total < 2000">
                                <i class="bi bi-info-circle me-1"></i><span>Minimum order amount EUR 2000</span>
                            </p>
                            <div class="mt-3">
                                <label for="orderNote">Note</label>
                                <textarea id="orderNote" class="form-control form-control-sm" rows="2" ng-value="order.order_note"
                                    ng-if="order.order_status == 0"></textarea>
                                <p ng-bind="order.order_note" ng-if="order.order_status > 0" class="small fw-bold"></p>
                            </div>
                            <div ng-if="order.order_status == 0" class="mt-4">
                                <div class="d-flex">
                                    <button class="btn btn-outline-success rounded-pill px-3 btn-sm me-auto"
                                        ng-click="orderStatus(2)"
                                        ng-disabled="order.order_total < 2000 || !minQty() || statusSubmit">Place
                                        Order</button>
                                    <button class="btn btn-outline-danger rounded-pill px-3 btn-sm"
                                        ng-disabled="statusSubmit" ng-click="orderStatus(1)">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div ng-if="!fn.objectLen(parsedProducts)" class="text-center my-5">
            <h1 class="text-secondary bi bi-cart3 display-1 mb-4"></h1>
            <h6>Your order's cart is empty</h6>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', ['ngSanitize'], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope, $timeout) {
            $scope.mediaPath = 'https://dash.sofieamoura.com/public/media/product';
            $scope.fn = NgFunctions;
            $scope.statusObject = {
                name: [
                    'Draft', 'Canceled', 'Placed',
                    'Confirmed', 'Advance Payment Is Pending',
                    'Balance Payment Is Pending', 'Shipped'
                ],
            };
            $scope.statusSubmit = false;
            $scope.qtyUpdate = false;
            $scope.min_qty = 6;
            $scope.focusedQty = 0;
            $scope.orderDisc = 0;
            $scope.orderQty = 0;
            $scope.order = <?= json_encode($order) ?>;
            $scope.products = <?= json_encode($products) ?>;
            $scope.parsedProducts = {};
            $scope.parseProducts = function() {
                $scope.parsedProducts = {};
                $scope.orderQty = 0;
                $.map($scope.products, function(p) {
                    if (typeof $scope.parsedProducts[p.prodcolor_slug] == 'undefined')
                        $scope.parsedProducts[p.prodcolor_slug] = {
                            info: p,
                            sizes: [],
                            qty: 0,
                            total: 0
                        };
                    $scope.parsedProducts[p.prodcolor_slug].sizes.push(p);
                    $scope.parsedProducts[p.prodcolor_slug].qty += +p.ordprod_request_qty;
                    $scope.parsedProducts[p.prodcolor_slug].total += +p.ordprod_request_qty * +p
                        .ordprod_price;
                    $scope.orderQty += +p.ordprod_request_qty;
                });
            }

            $scope.delSize = function(id) {
                $.post('/orders/remove', {
                    order: $scope.order.order_id,
                    product: id,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error deleting please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            };

            $scope.updateQty = function(id, qty) {
                $scope.qtyUpdate = true;
                $.post('/orders/update_qty', {
                    order: $scope.order.order_id,
                    product: id,
                    qty: qty,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        $scope.qtyUpdate = false;
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error updating qty please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }

            $scope.orderStatus = function(status) {
                if (status == 2 && !$('#orderForm').valid()) return;
                $scope.statusSubmit = true;

                var request = {
                    order: $scope.order.order_id,
                    retailer: $scope.order.order_retailer,
                    note: $('#orderNote').val(),
                    status: status,
                    _token: '{{ csrf_token() }}',
                };

                if (status == 2) {
                    request.bill_address = $('#bill_address').val();
                    request.ship_address = $('#ship_address').val();
                    $('input.address-input').each(function() {
                        request[$(this).attr('name')] = $.trim($(this).val());
                    });
                }

                $.post('/orders/update_status', request, function(response) {
                    scope.$apply(() => {
                        scope.statusSubmit = false;
                        if (response.status) {
                            scope.order = response.data;
                        } else toastr.error(response.message);
                    });
                }, 'json');
            }

            $scope.minQty = function() {
                return Math.min(...Object.values(scope.parsedProducts).map(e => e.qty)) >= $scope.min_qty;
            }

            $scope.parseProducts();

            $timeout(function() {
                $('#orderForm').validate();
                // $('.address-toggle-btn').off().on('click', function(e) {
                //     e.preventDefault();
                //     var target = $(this).data('target');
                //     console.log(target);
                //     $(target).slideToggle();
                //     $(this).toggleClass('bi-dash-square bi-plus-square');
                // });
            }, 250);
            scope = $scope;
        });
    </script>
@endsection
