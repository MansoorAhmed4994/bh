<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">


    
    
    <style>
    .modalFeild {
	margin-bottom: 60px;
}

.modalFeild.last {
	margin-bottom: 10px;
}

.modalFeild h6 {
	font-size: 15px;
	color: #0b0b0b;
	line-height: 1.2;
	font-weight: 500;
	padding-bottom: 10px;
	position: relative;
	display: flex;
}

.modalFeild h6:after {
	content: '';
	background-color: rgb(0, 15, 0);
	opacity: 0.302;
	width: 100%;
	height: 1.5px;
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	box-shadow: 0px 5px 9px 0px rgba(0, 0, 0, 0);
	transition: all 0.4s ease-in-out;
}

.modalFeild:hover h6:after {
	background-color: rgb(244, 103, 17);
	box-shadow: 0px 5px 9px 0px rgba(0, 0, 0, 0.75);
	transition: all 0.4s ease-in-out;
}

.modalFeild h6 em {
	font-weight: 300;
	padding-left: 5px;
}

.modalFeild h5 {
	font-size: 40px;
	font-weight: 300;
	color: #0b0b0b;
	line-height: 1.2;
	border-top: 1px solid #b2b7b2;
	border-bottom: 1px solid #b2b7b2;
	padding: 15px 0 25px;
}
.modalFeild h5 span{color:#f46711;font-weight:700;}

.parcelStatus-list li{position:relative;}
.parcelStatus-list li.statusdone{font-style:italic;}
.parcelStatus-list li div{padding:0 0 0 60px;position:relative;margin-left:25px;min-height:250px;display:flex;align-items:center;}
.parcelStatus-list li div:after{content:'';width:100%;height:1px;background-color:#b2b7b2;position:absolute;left:0;bottom:0;margin:auto;}
.parcelStatus-list li div:before{content:'';border-radius:50%;background-color:rgb(244,97,23);width:12px;height:12px;position:absolute;bottom:-5px;right:0;margin:auto;z-index:1;}
.parcelStatus-list li em{display:block;padding-bottom:10px;border-radius:20px;padding:10px 0;}
.parcelStatus-list li span{font-size:16px;color:#0b0b0b;line-height:1.2;display:flex;padding-bottom:10px;}
.parcelStatus-list li span i{color:#f46117;font-size:15px;margin-right:10px;width:15px;text-align:center;}
.parcelStatus-list li:after{content:'\f2f7';font-family:'Font Awesome 5';width:50px;height:50px;border-radius:50%;background-color:#f46117;line-height:50px;font-weight:900;font-size:20px;color:#fff;text-align:center;position:absolute;top:0px;bottom:0;left:0px;margin:auto;font-style:normal;transition:all 0.4s ease-in-out;}
.parcelStatus-list li.statusdone:after{content:"\f2f7";font-size:15px;background-color:#008000;width:50px;height:50px;line-height:50px;left:0px;}
.parcelStatus-list li:before{content:'';width:3px;height:100%;background-color:#f46117;position:absolute;top:0;bottom:-250px;margin:auto;left:24px;}
.parcelStatus-list li.last:before{background:rgb(244,97,23);background:linear-gradient(180deg,rgba(244,97,23,1) 50%,rgba(0,0,0,0) 50%);}

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
        argin-top: 25%;
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
                <a class="navbar-brand" href="#">Brand</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                @guest
                    @include('layouts.login')
                    
                @else
                    <ul class="navbar-nav">
                        <li class="nav-item active"> <a class="nav-link" href="{{route('dashboard')}}">dashboard </a> </li>
                        <li class="nav-item dropdown" id="myDropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manual Orders</a>
                        <ul class="dropdown-menu">
                            <li> <a class="dropdown-item" href="{{route('ManualOrders.create')}}"> Add New order </a></li>
                            <li> <a class="dropdown-item" href="{{route('ManualOrders.index')}}"> List</a></li>
                            <li> <a class="dropdown-item" href="{{route('ManualOrders.dipatch.bulk.orders')}}">Dispatch Bulk Orders</a></li>
                        </ul>
                        
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto"> 
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Profile </a>
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
