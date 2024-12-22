@if(session()->has('success'))
<script>
    alert("{{ session()->get('success') }}")
</script>
<div class="alert alert-success" role="alert">
  <center><h3>{{ session()->get('success') }}</h3></center>
</div>
@endif

@if(session()->has('errors'))
<script>
    alert("{{ session()->get('errors') }}")
</script>
<div class="alert alert-danger" role="alert">
  <center><h3>{{ session()->get('errors') }}</h3></center>
</div>
@endif

<script type="application/javascript">
    toastr.options = 
    {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "show",
        "hideMethod": "fadeOut"
        };
</script>