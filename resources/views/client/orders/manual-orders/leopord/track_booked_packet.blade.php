<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<title>Shop Order Tracking - Bootdey.com</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    	body{margin-top:20px;}

.steps .step {
    display: block;
    width: 100%;
    margin-bottom: 35px;
    text-align: center
}

.steps .step .step-icon-wrap {
    display: block;
    position: relative;
    width: 100%;
    height: 80px;
    text-align: center
}

.steps .step .step-icon-wrap::before,
.steps .step .step-icon-wrap::after {
    display: block;
    position: absolute;
    top: 50%;
    width: 50%;
    height: 3px;
    margin-top: -1px;
    background-color: #e1e7ec;
    content: '';
    z-index: 1
}

.steps .step .step-icon-wrap::before {
    left: 0
}

.steps .step .step-icon-wrap::after {
    right: 0
}

.steps .step .step-icon {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
    border: 1px solid #e1e7ec;
    border-radius: 50%;
    background-color: #f5f5f5;
    color: #374250;
    font-size: 38px;
    line-height: 81px;
    z-index: 5
}

.steps .step .step-title {
    margin-top: 16px;
    margin-bottom: 0;
    color: #606975;
    font-size: 14px;
    font-weight: 500
}

.steps .step:first-child .step-icon-wrap::before {
    display: none
}

.steps .step:last-child .step-icon-wrap::after {
    display: none
}

.steps .step.completed .step-icon-wrap::before,
.steps .step.completed .step-icon-wrap::after {
    background-color: #0da9ef
}

.steps .step.completed .step-icon {
    border-color: #0da9ef;
    background-color: #0da9ef;
    color: #fff
}

@media (max-width: 576px) {
    .flex-sm-nowrap .step .step-icon-wrap::before,
    .flex-sm-nowrap .step .step-icon-wrap::after {
        display: none
    }
}

@media (max-width: 768px) {
    .flex-md-nowrap .step .step-icon-wrap::before,
    .flex-md-nowrap .step .step-icon-wrap::after {
        display: none
    }
}

@media (max-width: 991px) {
    .flex-lg-nowrap .step .step-icon-wrap::before,
    .flex-lg-nowrap .step .step-icon-wrap::after {
        display: none
    }
}

@media (max-width: 1200px) {
    .flex-xl-nowrap .step .step-icon-wrap::before,
    .flex-xl-nowrap .step .step-icon-wrap::after {
        display: none
    }
}

.bg-faded, .bg-secondary {
    background-color: #f5f5f5 !important;
}
    </style>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
</head>


<body style="background: #ffc229;">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    @if(count($bookeddpacketdetails->packet_list) > 0)
    @foreach($bookeddpacketdetails->packet_list as $bookeddpacketdetail)
        <div class="container padding-bottom-3x mb-1">
            <div class="card mb-3">
                <div class="p-4 text-center text-white text-lg bg-dark rounded-top" ><span class="text-uppercase">Tracking Order No - </span><span class="text-medium">{{$bookeddpacketdetail->track_number}}</span></div>
                    <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between py-3 px-2 bg-secondary" style="background-color: rgb(209 233 255)!important">
                        <div class="w-100 text-center py-1 px-2"><span class="text-medium">Customer Order#:</span> {{$bookeddpacketdetail->booked_packet_order_id}}</div>
                        <div class="w-100 text-center py-1 px-2"><span class="text-medium">Customer Name:</span> {{$bookeddpacketdetail->consignment_name_eng}}</div>
                        <div class="w-100 text-center py-1 px-2"><span class="text-medium">Customer Contact#:</span> {{$bookeddpacketdetail->consignment_phone}}</div>
                        <div class="w-100 text-center py-1 px-2"><span class="text-medium">Customer Address:</span> {{$bookeddpacketdetail->consignment_address}}</div>
                    </div>
                    <div class="card-body">
                        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                            
                            <div class="step completed">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="pe-7s-cart"></i></div>
                                </div>
                                <h4 class="step-title"></h4>
                            </div>
                            
                            <div class="step completed">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="pe-7s-config"></i></div>
                                </div>
                                <h4 class="step-title">Dispacthed</h4>
                            </div>
                            
                             
                             
                                @if($bookeddpacketdetail->booked_packet_status == 'Delivered')
                                
                                    <div class="step completed">
                                        <div class="step-icon-wrap">
                                            <div class="step-icon"><i class="pe-7s-home"></i></div>
                                        </div>
                                        <h4 class="step-title">{{$bookeddpacketdetail->booked_packet_status}}</h4>
                                    </div>
                                    
                                @else
                                
                                    <div class="step completed">
                                        <div class="step-icon-wrap">
                                            <div class="step-icon"><i class="pe-7s-car"></i></div>
                                        </div>
                                        <h4 class="step-title">{{$bookeddpacketdetail->booked_packet_status}}</h4>
                                    </div>
                                    
                                    <div class="step">
                                        <div class="step-icon-wrap">
                                            <div class="step-icon"><i class="pe-7s-home"></i></div>
                                        </div>
                                        <h4 class="step-title">Product Delivered</h4>
                                    </div>
                                
                                @endif 
                            
                             
                             
                        
                        </div>
                    </div>
                </div>
                
                <!--<div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-sm-between align-items-center">-->
                <!--    <div class="custom-control custom-checkbox mr-3">-->
                <!--    <input class="custom-control-input" type="checkbox" id="notify_me" checked>-->
                <!--    <label class="custom-control-label" for="notify_me">Notify me when order is delivered</label>-->
                <!--    </div>-->
                <!--    <div class="text-left text-sm-right"><a class="btn btn-outline-primary btn-rounded btn-sm" href="orderDetails" data-toggle="modal" data-target="#orderDetails">View Order Details</a>-->
                <!--    </div>-->
                <!--</div>-->
        </div>
        
        <div class="container"> 
        
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th colspan="4">Status</th> 
                        <th colspan="4">Activity Datetime</th>
                        <th colspan="4">Reciever Name</th>
                        <th colspan="4">Reason</th>
                        
                        
                    </tr>
                </thead>
                
            
            @foreach($bookeddpacketdetail->{'Tracking Detail'} as $trackingdetail) 
            
                <tbody> 
                    <tr>
                        <td colspan="4">{{$trackingdetail->Status}}</td> 
                        <td colspan="4">{{$trackingdetail->Activity_datetime}}</td>
                        <td colspan="4">@if(isset($trackingdetail->Reciever_Name)){{$trackingdetail->Reciever_Name}}@endif</td>
                        <td colspan="4">@if(isset($trackingdetail->Reciever_Name)){{$trackingdetail->Reason}}@endif</td>
                        
                        
                        
                    </tr>
                </tbody>
                
            @endforeach
            
            </table>
            
        </div>
            
    @endforeach
     @endif
    
    
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
    	
    </script>
    
    <img src="{{ asset('public/images/thankyou.jpg') }}"  style="
    width: 500px;
    margin: 0 41%;
    background: #ffc229;
">
</body>
</html>
  <!--@dd($bookeddpacketdetails)-->
