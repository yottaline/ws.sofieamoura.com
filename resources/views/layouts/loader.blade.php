<div class="text-center text-secondary">
    <div ng-if="!loading && !list.length" class="py-5">
        <i class="bi bi-exclamation-circle display-3"></i>
        <h5>No Results</h5>
    </div>
    <div ng-if="loading" class="py-5">
        <span class="loading-spinner spinner-border spinner-border-sm text-secondary me-2" role="status"></span>
        <span>Loading...</span>
    </div>
    {{-- <div ng-if="!loading && list.length && noMore" class="small text-secondary">No Further Results</div> --}}
    <script>
        $(function() {
            $(window).scroll(function() {
                if ($(window).scrollTop() >= ($(document).height() - $(window).height() - 80) &&
                    !scope.loading && !scope.noMore) scope.load();
            });
        });
    </script>
</div>
