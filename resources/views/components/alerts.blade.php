@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.toast.success("{{ session('success') }}");
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.toast.error("{{ session('error') }}");
        });
    </script>
@endif