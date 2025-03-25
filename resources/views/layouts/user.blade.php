<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>  
        @include('layouts.libraries') 
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
            <nav class="navbar navbar-expand-lg navbar-dark  bg-primary">
                <div class="container-fluid"> 
                    
                    <a class="navbar-brand" href="{{route('admin.dashboard')}}"><img src="{{ asset('public/images/logo.png') }}" class="bh-logo"/></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="main_nav">
                    @guest
                        <ul class="navbar-nav ms-auto"> 
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Profile </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                <li><a class="dropdown-item" href="{{ route('login') }}"> Login </a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}"> Register </a></li>
                                </ul>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav">
                            <li class="nav-item active"> <a class="nav-link" href="{{route('user.dashboard')}}">dashboard </a> </li> 
                            <li class="nav-item dropdown" id="myDropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manual Orders</a>
                                <ul class="dropdown-menu">
                                    <li> <a class="dropdown-item" href="{{route('ManualOrders.create')}}"> Add Order</a></li>
                                    <li> <a class="dropdown-item" href="{{route('ManualOrders.index')}}"> List</a></li> 
                                    <li> <a class="dropdown-item" href="{{route('ManualOrders.details')}}"> details (View Order)</a></li>
                                    <li> <a class="dropdown-item" href="{{route('customer.payments.index')}}">Customer Payment</a></li> 
                                    <li> <a class="dropdown-item" href="{{route('manualOrders.quick.search')}}">Quick Order Actions</a></li> 
                                    
                                    <li> <a class="dropdown-item" href="{{route('product.demand.list')}}">Demand</a></li>
                                </ul> 
                            </li>
                            <li class="nav-item dropdown" id="myDropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Inventory</a>
                                <ul class="dropdown-menu">
                                    <li> <a class="dropdown-item" href="{{route('inventory.index')}}"> Manage Inventory </a></li> 
                                    <li> <a class="dropdown-item" href="{{route('inventory.pos')}}"> POS </a></li>
                                </ul> 
                            </li>
                            <li class="nav-item dropdown" id="myDropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Loadsheets</a>
                                <ul class="dropdown-menu">
                                    <li> <a class="dropdown-item" href="{{route('ManualOrders.dipatch.bulk.orders')}}">Local Loadsheet</a></li> 
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
                        <ul class="navbar-nav ms-auto"> 
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> {{auth()->user()->first_name}}</a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form class="dropdown-item" id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> 
                                </li>
                                </ul>
                            </li>
                        </ul>
                    @endguest
                    </div> 
                </div> 
            </nav>
    
            <main class="py-4">
                @include('layouts.universal_features.slider')
                @include('layouts.alerts')
                @yield('content')
            </main>
        </div>
         
        <script type="application/javascript">
            document.addEventListener("DOMContentLoaded", function()
            { 
                if (window.innerWidth < 992) 
                { 
                    document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown)
                    {
                        everydropdown.addEventListener('hidden.bs.dropdown', function () 
                        { 
                            this.querySelectorAll('.submenu').forEach(function(everysubmenu)
                            { 
                                everysubmenu.style.display = 'none';
                            });
                        })
                    });
        
                    document.querySelectorAll('.dropdown-menu a').forEach(function(element)
                    {                                                                                                        
                        element.addEventListener('click', function (e) 
                        {
                        let nextEl = this.nextElementSibling;
                        if(nextEl && nextEl.classList.contains('submenu')) 
                        {	 
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
            });  
        </script>
    </body>
</html>
