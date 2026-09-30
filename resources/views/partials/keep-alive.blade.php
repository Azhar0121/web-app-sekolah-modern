@auth
<script>
(function() {
    'use strict';
    const PING_INTERVAL = 600000; // 10 menit
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            fetch("{{ route('ping') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).catch(function() {});
        }
    }, PING_INTERVAL);
})();
</script>
@endauth
