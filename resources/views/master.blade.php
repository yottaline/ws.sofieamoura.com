<!DOCTYPE html>
<html lang="en" dir="ltr">

@include('layouts.head')

<body id="page-top">
    <nav class="navbar px-2 fixed-top" style="background-color: #fff;">
        <div class="d-flex w-100">
            <div class="me-auto px-3">
                <a class="navbar-brand fw-bold" href="/">
                    <img src="/assets/img/logo.png" alt="Sofie Amoura" draggable="false" width="180"></a>
            </div>
            @yield('search')
            <div class="px-3">
                @yield('cart')
                <a href="/profile" class="h6 m-0 d-inline-block link-secondary bi bi-person-circle p-2"></a>
            </div>
        </div>
    </nav>

    <div id="wrapper">
        @yield('content')
    </div>
</body>

@yield('js')

</html>
