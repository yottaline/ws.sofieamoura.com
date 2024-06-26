<div class="list-group">
    <a href="/profile" class="list-group-item list-group-item-action">
        <i class="bi bi-person-gear pe-2 text-secondary"></i>Account Details
    </a>
    <a href="/profile/orders" class="list-group-item list-group-item-action">
        <i class="bi bi-list-check pe-2 text-secondary"></i>Orders History
    </a>
    <a href="" id="logoutBtn" class="list-group-item list-group-item-action">
        <i class="bi bi-power pe-2 text-secondary"></i>Sign out
    </a>
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
