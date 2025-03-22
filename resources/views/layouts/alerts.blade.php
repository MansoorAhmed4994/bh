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
