@extends('master')
@section('title', 'Home')

@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control form-control-sm py-1 border-0 border-bottom"
            placeholder="Search...">
    </form>
@endsection

@section('style')
    <style>
        :root {
            --product-size: 240px;
        }

        .product-box {
            display: block;
            width: var(--product-size);
            margin: auto;
            margin-bottom: 15px;
            text-align: center
        }

        .product-img {
            height: var(--product-size);
            width: var(--product-size);
            margin: 0 auto 10px;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .order-img {
            width: 70px;
            height: 70px;
            background-position: center 0;
            background-repeat: no-repeat;
            background-size: contain;
        }

        #preview-area {
            padding: 20px;
            display: none;
            position: fixed;
            inset: 0;
            z-index: 10000;
            background-color: rgba(0, 0, 0, .9);
        }

        #preview-area .zoom_canvas {
            /* margin: 20px;
                                                                                                                                                                        padding: 10px; */
            background-repeat: no-repeat;
            background-position: 50%;
            cursor: crosshair;
            /* -webkit-transition: all 0.2s;
                                                                                                                                                                        -moz-transition: all 0.2s;
                                                                                                                                                                        -o-transition: all 0.2s;
                                                                                                                                                                        transition: all 0.2s; */
        }

        #preview-area .close-btn {
            font-size: 20px;
            position: absolute;
            line-height: 35px;
            text-align: center;
            right: 15px;
            top: 15px;
            display: block;
            height: 35px;
            width: 35px;
            background: rgba(0, 0, 0, .7);
            border-radius: 50%;
        }

        #thumbs {
            text-align: center;
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        #thumbs>li {
            display: inline-block;
            /* height: 100px; */
            /* width: 100px; */
            margin: 10px;
        }

        a.thumb-opt:link,
        a.thumb-opt:visited {
            display: block;
            height: 100px;
            /* border: 1px solid #f2f2f2; */
            /* margin-top: 15px; */
            /* background-color: #1d1d1d; */
        }

        /* a.thumb-opt:hover,
                                                    a.thumb-opt:focus,
                                                    a.thumb-opt:active,
                                                    a.thumb-opt.active {
                                                        border-color: #1d1d1d;
                                                    } */

        /* a.thumb-opt:hover>img,
                                                    a.thumb-opt:focus>img,
                                                    a.thumb-opt:active>img {
                                                        opacity: .9
                                                    } */

        .thumb-opt>img {
            /* width: 100%; */
            height: 100%;
            transition: all .25s ease
        }

        .carousel-item {
            cursor: zoom-in;
            height: 400px;
            line-height: 400px;
            text-align: center;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            transition: transform .6s ease, opacity .3s ease-out
        }
    </style>
@endsection

@section('cart')
    <a href="" id="cartBtn" class="h6 m-0 d-inline-block link-secondary bi bi-cart3 me-1 p-2 position-relative">
        @if (!empty($cart))
            <span class="position-absolute translate-middle bg-danger border border-light rounded-circle"
                style="right: -2px; top: 13px; padding: 5px"></span>
        @endif
    </a>
    <script>
        var cartCanvas;
        $(function() {
            cartCanvas = new bootstrap.Offcanvas('#cartCanvas');
            $('#cartBtn').on('click', function(e) {
                e.preventDefault();
                cartCanvas.show();
            });
        });
    </script>
@endsection

@section('content')
    <div class="container" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="card card-box">
            <div class="card-body">
                <h5 ng-if="q" class="py-4 small">Results for <span class="text-primary" ng-bind='q'></span></h5>
                <div ng-if="list.length" class="row">
                    <div ng-repeat="(pk, p) in list" class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <a href="" class="product-box" ng-click="viewProduct(pk)">
                            <div ng-if="p.prodcolor_media == null" class="product-img rounded mb-2"
                                style="background-image: url(/assets/img/default_product_image.png)"></div>
                            <div ng-if="p.prodcolor_media" class="product-img rounded mb-2"
                                style="background-image: url(<% mediaPath %>/<% p.product_id %>/<% p.media_file %>)">
                            </div>
                            <h6 class="mb-0 text-dark fw-bold" ng-bind="p.product_name"></h6>
                            <h6 class="small mb-0 text-secondary font-monospace" ng-bind="p.product_code"></h6>
                            <h6 class="small mb-0 font-monospace text-dark"
                                ng-bind="'EUR ' + fn.toFixed(+p.prodsize_wsp, 2)"></h6>
                        </a>
                    </div>
                </div>
                @include('layouts.loader')
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="productCanvas" aria-labelledby="productCanvasLabel">
            <div ng-if="selectedProduct !== false" class="offcanvas-header">
                <h5 class="offcanvas-title font-monospace small" id="productCanvasLabel"
                    ng-bind="'#' + list[selectedProduct].product_code"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div ng-if="selectedProduct !== false" class="offcanvas-body">
                <div ng-if="media.length">
                    <div id="carousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
                        <div class="carousel-indicators d-lg-none">
                            <button ng-repeat="m in media" type="button" ng-class="$first ? 'active' : ''"
                                data-bs-target="#carousel" data-bs-slide-to="<% $index %>"></button>
                        </div>
                        <div class="carousel-inner">
                            <div ng-repeat="m in media" class="carousel-item" data-type="1"
                                ng-class="$first ? 'active' : ''"
                                data-image="<% mediaPath %>/<% m.product_id %>/<% m.media_file %>"
                                style="background-image: url(<% mediaPath %>/<% m.product_id %>/<% m.media_file %>)"></div>
                        </div>
                    </div>

                    <div class="d-none d-lg-block mt-3">
                        <ul id="thumbs">
                            <li ng-repeat="m in media">
                                <a href="" class="thumb-opt rounded" data-slide-to="<% $index %>"
                                    ng-class="$first ? 'default-photo active' : ''" alt=""
                                    data-image="<% mediaPath %>/<% m.product_id %>/<% m.media_file %>"
                                    data-zoom-image="<% mediaPath %>/<% m.product_id %>/<% m.media_file %>">
                                    <img src="<% mediaPath %>/<% m.product_id %>/<% m.media_file %>">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- <div ng-if="list[selectedProduct].prodcolor_media == null" class="product-img rounded"
                    style="background-image: url(/assets/img/default_product_image.png)"></div>
                <div ng-if="list[selectedProduct].prodcolor_media" class="product-img rounded"
                    style="background-image: url(<% mediaPath %>/<% list[selectedProduct].product_id %>/<% list[selectedProduct].media_file %>)">
                </div> --}}

                <h6 class="fw-bold" ng-bind="list[selectedProduct].product_name"></h6>
                <h6 class="text-secondary small" ng-bind="list[selectedProduct].season_name"></h6>
                <div class="py-4">
                    <h6 class="fw-bold">Sizes</h6>
                    <div ng-if="colors === false" class="py-5 text-center">Loading...</div>
                    <div ng-if="colors !== false" class="table-responsive">
                        <div ng-repeat="(ck, c) in colors">
                            <div class="fw-bold text-danger bg-muted-2 px-2" ng-bind="c.info.prodcolor_name">
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr class="small" ng-repeat="(sk, s) in c.sizes">
                                        <td class="me-auto px-2" ng-bind="s.size_name"></td>
                                        <td width="70" class="font-monospace text-center" ng-bind="s.prodsize_wsp">
                                        </td>
                                        <td width="60">
                                            <input class="font-monospace text-center w-100 small prodsize-qty"
                                                data-wsp="<% s.prodsize_wsp %>" data-size="<% ck+','+sk %>"
                                                ng-model="s.qty" ng-change="calProductTotal()">
                                        </td>
                                        <td width="100" class="px-2 font-monospace text-center"
                                            ng-bind="fn.toFixed(s.qty * s.prodsize_wsp, 2)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-2 d-flex">
                            <span class="fw-bold me-auto">Total</span>
                            <span class="fw-bold font-monospace" id="totalAmount">0.00</span>
                        </div>
                        <div class="px-2 d-flex">
                            <span class="fw-bold me-auto">Qty</span>
                            <span class="fw-bold font-monospace" id="totalQty">0</span>
                        </div>
                        <button class="btn btn-outline-dark w-100 btn-sm mt-4" ng-click="addToOrder()">Order</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="cartCanvas" aria-labelledby="cartCanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="cartCanvasLabel">Order's Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div ng-if="!fn.objectLen(order)" class="py-5 text-center">
                    <h1 class="display-4 bi bi-cart3 text-danger"></h1>
                    <span class="text-secondary">Your cart is empty</span>
                </div>
                <div ng-if="fn.objectLen(order)">
                    <div ng-repeat="(ok, o) in order" class="mb-3 border-bottom">
                        <div class="d-flex">
                            <div ng-if="o.info.prodcolor_media == null" class="order-img rounded align-self-start m-1"
                                style="background-image:url({{ asset('assets/img/default_product_image.png') }})">
                            </div>
                            <div ng-if="o.info.prodcolor_media" class="order-img rounded align-self-start m-1"
                                style="background-image:url('<% mediaPath %>/<% o.info.product_id %>/<% o.info.media_file %>')">
                            </div>
                            <div class="flex-fill">
                                <h6 class="small">
                                    <span class="fw-bold me-2" ng-bind="o.info.product_name"></span>
                                    <span class="font-monospace text-secondary" ng-bind="'#'+o.info.product_code"></span>
                                </h6>
                                <table class="table mb-0">
                                    <tbody>
                                        <tr class="small" ng-repeat="(sk, s) in o.sizes">
                                            <td ng-bind="s.info.prodcolor_name"></td>
                                            <td width="40" class="text-center" ng-bind="s.info.size_name"></td>
                                            <td width="40" class="font-monospace text-center"
                                                ng-bind="s.info.prodsize_wsp">
                                            </td>
                                            <td width="30" class="font-monospace text-center" ng-bind="s.qty">
                                            </td>
                                            <td width="60" class="px-2 font-monospace text-center"
                                                ng-bind="fn.toFixed(s.total, 2)">
                                            </td>
                                            <td class="col-fit" ng-click="removeFromOrder(ok, sk)">
                                                <a href="" class="bi bi-x-circle link-danger"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="font-monospace small text-end py-2">
                            <div class="me-3 d-inline-block">Qty: <span ng-bind="o.qty"></span></div>
                            <div class="d-inline-block">Total: <span ng-bind="fn.toFixed(o.total, 2)"></span></div>
                        </div>
                    </div>
                    <div class="px-2 d-flex">
                        <span class="fw-bold me-auto">Total</span>
                        <span class="fw-bold font-monospace" ng-bind="fn.toFixed(orderTotal, 2)">0.00</span>
                    </div>
                    <div class="px-2 d-flex">
                        <span class="fw-bold me-auto">Qty</span>
                        <span class="fw-bold font-monospace" ng-bind="orderQty">0</span>
                    </div>
                    <div class="mt-3">
                        <label for="orderNote">Note</label>
                        <textarea id="orderNote" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <p ng-if="+orderTotal < 2000" class="m-0 mt-3 text-danger">
                        <i class="bi bi-info-circle me-1"></i>Min order amount EUR 2000
                    </p>
                    <button class="btn btn-outline-dark w-100 btn-sm mt-3" ng-click="placeOrder()"
                        ng-disabled="!fn.objectLen(order) || submitting || orderTotal < 2000">
                        <span ng-if="submitting" class="spinner-border spinner-border-sm me-2"
                            role="status"></span>Place Order</button>
                </div>
            </div>
        </div>
    </div>
    <div id="preview-area">
        <a href="#" class="close-btn text-light float-end bi bi-x"></a>
        <div class="zoom_canvas h-100"></div>
    </div>
@endsection

@section('js')
    <script>
        const productCanvas = new bootstrap.Offcanvas('#productCanvas'),
            img = new Image(),
            preloaderImg = "{{ asset('/assets/img/preloader/preloader-white-32.gif') }}";

        const validateEmail = email => {
            return String(email).toLowerCase().match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        const zoomController = function(e) {
            e.preventDefault();
            var zoom_canvas = $(this),
                xP = '50%',
                yP = '50%',
                x = 0,
                y = 0,
                pageX = e.pageX || e.originalEvent.touches[0].pageX,
                pageY = e.pageY || e.originalEvent.touches[0].pageY,
                width = zoom_canvas.width(),
                height = zoom_canvas.height();

            if (img.naturalWidth > width) {
                x = pageX - zoom_canvas.offset().left;
                xP = parseFloat((x / width * 100).toFixed(2));
                xP = xP > 100 ? 100 : (xP < 0 ? 0 : xP);
            }
            if (img.naturalHeight > height) {
                y = pageY - zoom_canvas.offset().top;
                yP = parseFloat((y / height * 100).toFixed(2));
                yP = yP > 100 ? 100 : (yP < 0 ? 0 : yP);
            }

            console.log(`${xP}% ${yP}%`);
            zoom_canvas.css('background-position-x', `${xP}%`);
            zoom_canvas.css('background-position-y', `${yP}%`);
        }
        img.onload = function() {
            $('.zoom_canvas').css('background-image', `url(${img.src})`).on('mousemove touchmove', zoomController);
        };

        var scope, carouselElem, carousel,
            ngApp = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });
        ngApp.controller('ngCtrl', function($scope) {
            $scope.mediaPath = 'https://dash.sofieamoura.com/public/media/product';
            $scope.fn = NgFunctions;
            $scope.q = '';
            $scope.noMore = false;
            $scope.loading = false;
            $scope.submitting = false;
            $scope.list = [];
            $scope.media = [];
            $scope.offset = 0;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.offset = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                $.post("/products/load", {
                    offset: $scope.offset,
                    limit: limit,
                    q: $scope.q,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.offset += ln;
                        }
                    });
                }, 'json');
            }

            // order = {prod_ref: {info: {}, sizes: [{info: {}, qty: n, total: n},], qty: n, total: n},}
            $scope.order = {};
            $scope.orderQty = 0;
            $scope.orderTotal = 0;
            $scope.colors = false;
            $scope.selectedProduct = false;
            $scope.viewProduct = function(ndx) {
                if ($scope.submitting) return;
                $scope.selectedProduct = ndx;
                $scope.media = [];
                $scope.colors = false;
                productCanvas.show();
                $.post('/products/sizes_and_media', {
                    slug: $scope.list[$scope.selectedProduct].prodcolor_slug,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    var colors = {};
                    $.map(data.sizes, function(item) {
                        if (typeof colors[item.prodcolor_ref] == 'undefined')
                            colors[item.prodcolor_ref] = {
                                info: item,
                                sizes: [],
                            };
                        item.qty = 0;
                        colors[item.prodcolor_ref].sizes.push(item);
                    });
                    scope.$apply(() => {
                        scope.colors = colors;
                        scope.sizes = data.sizes;
                        scope.media = data.media;
                    });
                    scope.$evalAsync(() => {
                        carouselElem = document.querySelector('#carousel');
                        carousel = new bootstrap.Carousel(carouselElem, {
                            interval: 3000,
                            wrap: true
                        });

                        $('.prodsize-qty').on('focus', function(e) {
                            if ($(this).val() === '0') $(this).val('');
                        }).on('blur', function(e) {
                            if ($(this).val() === '') $(this).val('0');
                        });

                        $('.thumb-opt').on('click', function(e) {
                            e.preventDefault();
                            var indx = $('.thumb-opt').index(this);
                            carousel.to(indx);
                        });

                        $('.carousel-item').on('click', function(e) {
                            carousel.pause();
                            $('.zoom_canvas').removeClass('d-none');
                            $('.zoom_canvas')
                                .css('background-position', `center`)
                                .css('background-image', `url(${preloaderImg})`);
                            $('#preview-area').fadeIn(150);
                            img.src = $(this).data('image');
                        });
                    });
                }, 'json')
            }

            $scope.calProductTotal = function() {
                var total = 0,
                    qty = 0;
                $('.prodsize-qty').map(function(n, e) {
                    qty += +e.value;
                    total += (+e.value * $(e).data('wsp'));
                });
                $('#totalAmount').text(sepNumber(total.toFixed(2)));
                $('#totalQty').text(qty);
            }

            $scope.addToOrder = function() {
                var product_ref, totalQty = 0,
                    totalAmount = 0,
                    sizes = [];

                $('.prodsize-qty').map(function(n, e) {
                    var keys = $(e).data('size').split(','),
                        ck = keys[0],
                        sk = keys[1],
                        size = $scope.colors[ck].sizes[sk],
                        qty = +$(e).val(),
                        amount = qty * size.prodsize_wsp;

                    totalQty += qty;
                    totalAmount += amount;
                    product_ref = size.product_ref;

                    if (qty) sizes.push({
                        info: size,
                        qty: qty,
                        total: amount,
                    });
                });

                if (totalQty) {
                    $scope.order[product_ref] = {
                        info: $scope.list.find(o => o.product_ref == product_ref),
                        sizes: sizes,
                        qty: totalQty,
                        total: totalAmount,
                    };
                } else if (Object.keys($scope.order).includes(product_ref))
                    delete($scope.order[product_ref]);
                productCanvas.hide();
                $scope.calOrderTotal();
            }

            $scope.removeFromOrder = function(ok, sk) {
                if ($scope.submitting) return;
                $scope.order[ok].sizes.splice(sk, 1);
                if ($scope.order[ok].sizes.length) {
                    $scope.order[ok].qty = 0;
                    $scope.order[ok].total = 0;
                    $.map($scope.order[ok].sizes, e => {
                        $scope.order[ok].qty += e.qty;
                        $scope.order[ok].total += e.total;
                    });
                } else delete($scope.order[ok]);
                $scope.calOrderTotal();
            }

            $scope.calOrderTotal = function() {
                $scope.orderQty = 0;
                $scope.orderTotal = 0;
                $.map($scope.order, e => {
                    $scope.orderQty += e.qty;
                    $scope.orderTotal += e.total;
                });
            }

            $scope.placeOrder = function() {
                var note = $.trim($('#orderNote').val()),
                    obj = Object.values(scope.order),
                    qty = $(obj).map((n, e) => $(e.sizes).map((x, y) => y.qty).get()).get().join(),
                    sizes = $(obj).map((n, e) => $(e.sizes).map((x, y) => y.info.prodsize_id).get()).get()
                    .join();

                $scope.submitting = true;
                $.post('/orders/submit', {
                    qty: qty,
                    sizes: sizes,
                    note: note,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    scope.$apply(() => {
                        $scope.submitting = false;
                        if (response.status) $('.retailer-field').val(null);
                        $scope.order = {};
                        toastr.success('Order placed');
                    });
                }, 'json');
            }

            $scope.load();
            scope = $scope;
        });

        $(function() {
            $('#nvSearch').on('submit', function(e) {
                e.preventDefault();
                scope.$apply(() => {
                    scope.list = [];
                    scope.q = $.trim($(this).find('input').val());
                });
                scope.load(true);
            });

            $('#preview-area .close-btn').on('click', function(e) {
                e.preventDefault();
                $('#preview-area').fadeOut(150);
                $('.zoom_canvas').off();
            });
        });
    </script>
@endsection
