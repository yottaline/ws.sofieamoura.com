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
                <a href="/profile" class="d-inline-block link-dark bi bi-person-circle p-2"></a>
                {{-- <a href="" id="logoutBtn" class="d-inline-block link-dark bi bi-power p-2"></a> --}}
                <form id="logoutForm" action="{{ route('logout') }}" method="post" class="d-none">
                    @csrf
                    <button type="submit" class="d-none"><i class="bi bi-power text-danger"></i></button>
                </form>
                <script>
                    $(function() {
                        $('#logoutBtn').on('click', e => {
                            e.preventDefault();
                            $('#logoutForm').submit();
                        });
                    });
                </script>
            </div>
        </div>
    </nav>

    <div id="wrapper">
        @yield('content')
    </div>
</body>

@yield('js')

</html>
