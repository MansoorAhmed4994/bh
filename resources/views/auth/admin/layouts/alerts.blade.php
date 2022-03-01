@if(session()->has('success'))
<script>
    alert("{{ session()->get('success') }}")
</script>
@endif