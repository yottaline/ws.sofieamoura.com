<form id="logoutForm" action="{{ route('logout') }}" method="post">
    @csrf
    <div class="list-group list-group-flush mb-4">
        <a href="/profile" class="list-group-item list-group-item-action">
            <i class="bi bi-person-gear pe-2 text-secondary"></i>Account Info
        </a>
        <a href="/orders" class="list-group-item list-group-item-action">
            <i class="bi bi-list-check pe-2 text-secondary"></i>Orders History
        </a>
        <button type="submit" id="logoutBtn" class="list-group-item list-group-item-action">
            <i class="bi bi-power pe-2 text-secondary"></i>Sign out
        </button>
    </div>
</form>
