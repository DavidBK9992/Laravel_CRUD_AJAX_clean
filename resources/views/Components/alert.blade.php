<!-- Alert modal bootstrap -->
@if (session('success'))
    <div class="alert fixed z-50 alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 z-index-50"
        role="alert" id="success-alert">
        {{ session('success') }}
    </div>
    <!-- Auto close + OnClick close -->
    <script>
        function closeAlert() {
            document.getElementById('success-alert').style.display = "none";
        }
        // Auto hide after 3 seconds
        setTimeout(() => {
            closeAlert();
        }, 3000);
    </script>
@endif
