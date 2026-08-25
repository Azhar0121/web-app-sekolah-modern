<footer class="school-footer">
    <div class="footer-container">

        <div class="footer-brand">
            <div class="footer-logo">
                {{ strtoupper(substr(config('app.name'), 0, 1)) }}
            </div>
            <div>
                <strong>{{ config('app.name') }}</strong>
                <span>Modern School Management System</span>
            </div>
        </div>

        <div class="footer-copy">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </div>

    </div>
</footer>
