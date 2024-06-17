<!DOCTYPE html>
<html lang="en" dir="ltr">

@include('layouts.head')

<body id="page-top">
    <nav class="navbar px-2 fixed-top" style="background-color: #e3f2fd;">
        <div class="d-flex w-100 align-items-center">
            <div class="me-auto">
                <a class="navbar-brand fw-bold" href="/">Sofie Amoura</a>
            </div>
            @yield('search')
        </div>
    </nav>

    <div id="wrapper">
        @yield('content')
    </div>
</body>

@yield('js')

</html>
