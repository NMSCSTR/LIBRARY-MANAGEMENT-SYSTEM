@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
</script>
@endif
