<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!--flasher --> 
<script  src="{{ asset('public/vendor/flasher/flasher.min.js') }}" ></script>
<link href="{{ asset('public/vendor/flasher/flasher.min.css') }}" rel="stylesheet"> 



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
    
    
    <!--Select2 Drop down-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   
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
        
        
        .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) center no-repeat;
    }

    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden; 
        
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
        z-index: 11111;
    }
    </style>


    
</head>

<body>
    <div class="overlay">
        <div class="text-center" style="margin-top: 25%;">
            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div id="app"> 
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{ asset('public/images/brandhub_logo_100_x_100.png') }}" class="bh-logo"/></a>
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
                        <li class="nav-item active"> <a class="nav-link" href="{{route('manualOrders.quick.search')}}">Quick Search </a> </li>
                        <li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manual Orders</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.create')}}"> Add New order </a></li>
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.index')}}"> List</a></li>
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.details')}}"> details (View Order)</a></li> 
                                <li> <a class="dropdown-item" href="{{route('customer.payments.index')}}">Customer Payment</a></li> 
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.print.slip.by.scan')}}">Price Slip By Scan</a></li>
                                <li> <a class="dropdown-item" href="{{route('product.demand.list')}}">Demand</a></li>
                
                            </ul>
                        
                        
                        </li>
                        <li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Loadsheets</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.dipatch.bulk.orders')}}">Local Loadsheet</a></li> 
                                <li> <a class="dropdown-item" href="{{route('leopord.loadsheet')}}">Leopord Loadsheet</a></li> 
                                
                            </ul>
                        </li><li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Reports</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('ManualOrders.reports.printed.slips')}}">Printed Slips</a></li>
                                
                            </ul> 
                        </li>
                        <li class="nav-item dropdown" id="myDropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Inventory</a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item" href="{{route('inventory.index')}}"> Manage Inventory </a></li> 
                                <li> <a class="dropdown-item" href="{{route('inventory.pos')}}"> POS </a></li>
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
                            <li> <a class="dropdown-item" href="{{route('register')}}" target="_blank"> Register</a></li>
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
                        <li class="nav-item active"> 
                            <a class="nav-link" target="_blank" href="{{route('support.index')}}">Support </a> 
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
                                <form class="dropdown-item" id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
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
            @include('layouts.universal_features.slider')
            @include('layouts.alerts')
            @yield('content')
        </main>
    </div>
</body>    

    <script type="application/javascript">
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
</html>
