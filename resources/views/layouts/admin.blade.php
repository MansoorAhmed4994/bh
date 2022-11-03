<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="icon" href="https://scontent.fkhi6-2.fna.fbcdn.net/v/t39.30808-6/271851970_797859254942923_8384634057214477174_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=T_JF1ooZlfIAX8VAMOF&tn=qbJWCDzG88tDUCIH&_nc_ht=scontent.fkhi6-2.fna&oh=00_AT-PmcdiG8euvrHKf0u_aI6qtr6g2VjhlGuWaI8Ks1I_fA&oe=630B15D5">
    <title>{{ config('app.name', 'Brandhub') }}</title>

    <!-- Scripts -->
   <script  src="{{ asset('public/js/app.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
    
    <style>
        @media all and (min-width: 992px) {
            .dropdown-menu li{ position: relative; 	}
            .nav-item .submenu{ 
                display: none;
                position: absolute;
                left:100%; top:-7px;
            }
            .nav-item .submenu-left{ 
                right:100%; left:auto;
            }
            .dropdown-menu > li:hover{ background-color: #f1f1f1 }
            .dropdown-menu > li:hover > .submenu{ display: block; }
        }	
        /* ============ desktop view .end// ============ */

        /* ============ small devices ============ */
        @media (max-width: 991px) {
        .dropdown-menu .dropdown-menu{
            margin-left:0.7rem; margin-right:0.7rem; margin-bottom: .5rem;
        }
        }	
    </style>


    
</head>

<body>
    <div id="app"> 
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Brand</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                @guest
                    <ul class="navbar-nav ms-auto"> 
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Profile </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                            <li><a class="dropdown-item" href="{{ route('login') }}"> Login </a>
                            <li><a class="dropdown-item" href="{{ route('register') }}"> Register </a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav">
                        <li class="nav-item active"> <a class="nav-link" href="{{route('admin.dashboard')}}">dashboard </a> </li>
                        <li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manual Orders</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.create')}}"> Add New order </a></li>
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.index')}}"> List</a></li>
                
                            </ul>
                        
                        
                        </li>
                        <li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Inventory</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('inventory.index')}}"> Manage Inventory </a></li> 
                            </ul> 
                        </li>
                        <li class="nav-item dropdown" id="shipment">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Shipment</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('trax.create.bulk.booking.by.scan')}}"> Trax </a></li>
                                <li> <a class="dropdown-item" href="{{route('mnp.create.bulk.booking.by.scan')}}"> M&P</a></li> 
                            </ul> 
                        </li>
                        <li class="nav-item active"> 
                            <a class="nav-link" target="_blank" href="{{route('ManualOrders.track.order')}}">Track Order </a> 
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown" id="myDropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Users</a>
                        @can ('manage-users')
                        <ul class="dropdown-menu">
                            <li> <a class="dropdown-item" href="{{route('admin.user.index')}}"> List</a></li>
                        </ul>
                        @endcan
                        
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown" id="riders_dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Riders</a>
                        
                        <ul class="dropdown-menu">
                            <li> <a class="dropdown-item" href="{{route('riders.create')}}"> List</a></li>
                        </ul>
                        
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown" id="riders_dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Accounts</a>
                        
                        <ul class="dropdown-menu">
                            <li> <a class="dropdown-item" href="{{route('accounts.index')}}"> List</a></li>
                        </ul>
                        
                        </li>
                        <li class="nav-item active"> 
                            <a class="nav-link" target="_blank" href="{{route('inactive.customers')}}">Inactive Customers </a> 
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto"> 
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> {{ Auth::user()->first_name }} </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form class="dropdown-item" id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                </form> 
                            </li> 
                        </li>
                    </ul>
                @endguest
                </div>
            <!-- navbar-collapse.// -->
            </div>
            <!-- container-fluid.// -->
        </nav>

        <main class="py-4">
        @include('frontend.layouts.alerts')
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js">
    
        document.addEventListener("DOMContentLoaded", function(){
        // make it as accordion for smaller screens
        if (window.innerWidth < 992) {

        // close all inner dropdowns when parent is closed
        document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown){
            everydropdown.addEventListener('hidden.bs.dropdown', function () {
            // after dropdown is hidden, then find all submenus
                this.querySelectorAll('.submenu').forEach(function(everysubmenu){
                // hide every submenu as well
                everysubmenu.style.display = 'none';
                });
            })
        });

        document.querySelectorAll('.dropdown-menu a').forEach(function(element)
        {                                                                                                        
                element.addEventListener('click', function (e) {
                let nextEl = this.nextElementSibling;
                if(nextEl && nextEl.classList.contains('submenu')) {	
                // prevent opening link if link needs to open dropdown
                e.preventDefault();
                if(nextEl.style.display == 'block')
                {
                    nextEl.style.display = 'none';
                } 
                else 
                {
                    nextEl.style.display = 'block';
                }

            }
        });
  })
}
// end if innerWidth
}); 
// DOMContentLoaded  end
    </script>
</body>
</html>
