<!-- SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Notification Scripts -->
<script>
    // Configure Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Configure SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Success notification function
    function showSuccess(message) {
        toastr.success(message);
        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Error notification function
    function showError(message) {
        toastr.error(message);
        Toast.fire({
            icon: 'error',
            title: message
        });
    }

    // Warning notification function
    function showWarning(message) {
        toastr.warning(message);
        Toast.fire({
            icon: 'warning',
            title: message
        });
    }

    // Info notification function
    function showInfo(message) {
        toastr.info(message);
        Toast.fire({
            icon: 'info',
            title: message
        });
    }

    // Confirmation dialog function
    function showConfirm(title, message, callback) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    // Delete confirmation function
    function confirmDelete(title, message, callback) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    // Form submission with loading
    function submitFormWithLoading(formId, loadingText = 'Processing...') {
        const form = document.getElementById(formId);
        if (form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>${loadingText}`;
            
            form.submit();
        }
    }

    // Show session messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccess("{{ session('success') }}");
        @endif

        @if(session('error'))
            showError("{{ session('error') }}");
        @endif

        @if(session('warning'))
            showWarning("{{ session('warning') }}");
        @endif

        @if(session('info'))
            showInfo("{{ session('info') }}");
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showError("{{ $error }}");
            @endforeach
        @endif
    });
</script> 