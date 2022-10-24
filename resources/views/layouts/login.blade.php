<ul class="navbar-nav ms-auto"> 
        <li class="nav-item active"> <a class="nav-link" href="{{ route('admin.login') }}">Admin </a> </li>
        <li class="nav-item active"> <a class="nav-link" href="{{ route('login') }}">User </a> </li>
    <li class="nav-item dropdown"> 
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Profile </a>
        <ul class="dropdown-menu dropdown-menu-right">
        <li><a class="dropdown-item" href="{{ route('login') }}"> Login </a>
        <li><a class="dropdown-item" href="{{ route('register') }}"> Register </a>
    </li>
</ul>