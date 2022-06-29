@extends('layouts.app')

@section('content') 

 
  
<div class="container"> 
    <div class="d-flex justify-content-center">
        <nav class="navbar navbar-light bg-light">
        
        
        
          <form class="form-inline" method="post" action="{{ route('ManualOrders.get.order.list') }}">
              @csrf
            
            <div class="form-group">
                <select class="form-select" aria-label="Default select example" name="search_by" required>
                    <option selected value ="">Search By</option>
                    <option value="id">Order ID #</option>
                    <option value="consignment_id">Consignment #</option>
                    <option value="mobile">Mobile #</option> 
                </select> 
            </div>
            
            <div class="form-group">
                <input class="form-control mr-sm-2" type="search" name="id" placeholder="ID OR Number" aria-label="Search">
            </div>
            
            <div class="form-group">
                <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </div>
            
            
          </form>
          <!--<button type="button" class="btn btn-primary" data-target="myModalLabel" class="dropdown-item">Dispatched</button> -->
          
        </nav>
    </div>
    
       @isset($error)
            <div class="alert alert-warning" role="alert">
                {{$error}}
            </div> 
        @endisset
     
    @if(isset($shipment))
    	@if($shipment == 'mnp')
   
	      <div>
	         <div class="popinner modalform">
	            <div class="container">
	            
	               <form>
	                  <div class="row">
	                     <h4>M&P</h4>
	                     <div class="col-md-12">
	                        <div class="quotesec-hd mn-hd">
	                           <h5>Consignment &nbsp;<span> {{$Order_details->tracking_Details[0]->CN}}<b></b></span></h5>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Booking Date: </span> <em>{{$Order_details->tracking_Details[0]->BookingDate}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>CN: </span> <em>{{$Order_details->tracking_Details[0]->BookingDate}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Consignee Name: </span> <em>{{$Order_details->tracking_Details[0]->Consignee}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Shipper: </span> <em>{{$Order_details->tracking_Details[0]->Shipper}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Service Type: </span> <em>{{$Order_details->tracking_Details[0]->ServiceType}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>CN Status:</span> <em>{{$Order_details->tracking_Details[0]->CNStatus}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Pieces: </span> <em>{{$Order_details->tracking_Details[0]->pieces}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Weight: </span> <em>{{$Order_details->tracking_Details[0]->weight}}</em></h6>
	                        </div>
	                     </div>
	                     <div class="col-md-6">
	                        <div class="modalFeild">
	                           <h6><span>Received By: </span> <em>{{$Order_details->tracking_Details[0]->ReceivedBy}}</em></h6>
	                        </div>
	                     </div>
	                     <div id="viewdetails_div">
	                        <div class="col-md-12">
	                           <div class="modalFeild last">
	                              <h5>Parcel <span> Status.</span></h5>
	                           </div>
	                        </div>
	                        <div class="col-md-6">
	                           <ul class="parcelStatus-list">
	                               
	                               @foreach($Order_details->tracking_Details[0]->Details as $history)
	                              <li>
	                                 <div class="">
	                                     <em>
	                                         <span><i class="fas fa-calendar-day"></i> {{$history->DateTime}}</span>
	                                         <span><i class="fas fa-history"></i> {{$history->Detail}}</span>
	                                         <span><i class="far fa-location"></i> {{$history->Location}}</span>
	                                         <span><i class="fal fa-info"></i> {{$history-> Status}}</span>
	                                     </em>
	                                 </div>
	                              </li>
	                              @endforeach
	                           </ul>
	                        </div>
	                        
	                     </div>
	                     <hr>
	                  </div>
	               </form>
	            </div>
	         </div>
	      </div>
	      <div>
	         <div class="popinner modalform">
	            <div class="container">
	               <form></form>
	            </div>
	         </div>
	      </div>
	    @endif
	    
	    @if($shipment == 'trax')
	     
            <div class="tracking" id="tracking">
                <div class="mt-4 border-primary">
               
                    <h4>Trax</h4>
                      <div class="d-flex align-items-center bg-primary">
                         <div class="m-1 font-medium-3 white">{{$Order_details->details->tracking_number}}</div> 
                      </div>
                      <div class="p-1">
                          
                        <div class="row justify-content-between">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                               <h4><u>Shipper/Pickup Information</u></h4>
                               <div class="border table-responsive">
                                  <table class="table table-sm table-borderless mb-0">
                                     <tbody>
                                        <tr>
                                           <td><strong>Shipper</strong></td>
                                           <td>{{$Order_details->details->shipper->name}}</td>
                                        </tr>
                                        <tr>
                                           <td><strong>Origin</strong></td>
                                           <td>{{$Order_details->details->shipper->city}}</td>
                                        </tr>
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 mt-2 mt-xs-2 mt-sm-2 mt-md-2 mt-lg-0">
                               <h4><u>Consignee Information</u></h4>
                               <div class="border table-responsive">
                                  <table class="table table-sm table-borderless mb-0">
                                     <tbody>
                                        <tr>
                                           <td><strong>Consignee</strong></td>
                                           <td>{{$Order_details->details->consignee->name}}</td>
                                        </tr>
                                        <tr>
                                           <td><strong>Destination</strong></td>
                                           <td>{{$Order_details->details->consignee->destination}}</td>
                                        </tr>
                                        <tr></tr>
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                            
                            <div class="col-12 mt-2">
                               <h4><u>Tracking History</u></h4>
                               <div class="border table-responsive">
                                  <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                     <table class="table table-sm table-borderless datatable tracking_history dataTable no-footer" id="DataTables_Table_0" role="grid">
                                        <thead>
                                           <tr role="row">
                                              <th class="align-middle date_time sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Date / Time: activate to sort column ascending" style="width: 491.758px;"><strong>Date / Time</strong></th>
                                              <th class="align-middle status sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 673.867px;"><strong>Status</strong></th>
                                           </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Order_details->details->tracking_history as $history)
                                            
                                           <tr role="row" class="odd">
                                              <td class="align-middle date_time sorting_1">{{$history->date_time}}</td>
                                              <td class=" align-middle status">{{$history->status}}</td>
                                           </tr>
                                           @endforeach
                                        </tbody>
                                     </table>
                                  </div>
                               </div>
                            </div>
                        
                        </div>
                      </div>
                    </div>
                </div>
	        </div> 
	    @endif
	    
    @endif
    
    @isset($list)
    <table class="table table-bordered">
        <thead>
            <tr> 
                <th scope="col"></th>
                <th scope="col">#</th>
                <!--<th scope="col">Edit</th>-->
                <!--<th scope="col">view</th>-->
                <!--<th scope="col">Print</th>-->
                <!--<th scope="col">Prepared</th>-->
                <!--<th scope="col">Confirmed</th>-->
                <!--<th scope="col">Dispatched</th>-->
                <!--<th scope="col">deleted</th>-->
                <th scope="col">Actions</th>
                <th scope="col">Order ID</th>
                <th scope="col">First Name</th> 
                <th scope="col">Number</th>
                <th scope="col">Description</th>
                <!--<th scope="col">Ord. Location</th>-->
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Images</th>
                <th scope="col">Total Pieces</th>
                <th scope="col">Ord.paid Date</th>
                <th scope="col">created Date</th>
                <th scope="col">Updated Date</th>
                <th scope="col">Ord.status</th>
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($list as $lists)
            
            <tr >
                <th ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></th>
                <th scope="row"><?=$count?></th>  
                <th> 
                    @if($lists->consignment_id != 0)
                    <a class="btn btn-primary" href="{{route('ManualOrders.track.order.details',$lists->consignment_id)}}">Track</a>
                    @endif
                </th>
                <th>{{$lists->id}}</th>
                <th>{{$lists->first_name}}</th>  
                <th>{{$lists->number}}</th>  
                <!--<th><button type="button" onclick="generateLink(this.value)">{{$lists->description}}</button></th> -->
                <th>{{$lists->description}}</th>
                <!--<th>{{$lists->order_delivery_location}}</th>-->
                <th>{{$lists->reciever_address}}</th>
                <th>{{$lists->price}}</th>
                <th style="display: flex;">
                    @if(!empty($lists->images))
                        @foreach(explode('|', $lists->images) as $image)   
                        <img class="pop rounded float-left" style="margin-right: 5px;" src="{{asset($image)}}" alt="Card image cap" width="50" height="50">
                        @endforeach
                    @endif
                </th>
                <th>{{$lists->total_pieces}}</th>
                <th>{{$lists->date_order_paid}}</th>
                <th>{{$lists->created_at}}</th>
                <th>{{$lists->updated_at}}</th>
                <th>{{$lists->status}}</th>
            </tr>
            <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
     
    @endisset
    
    
<script>
    print_mnp_slips
    var base_url = '<?php echo e(url('/')); ?>';

         

        $( document ).ready(function() {
            
            $('#print_mnp_slips').on('click',function(e)
            {  
                 
                $.ajax({
                    headers: {
                        
                        "cache-control": "no-cache",
                        "content-length": "36",
                        "content-type": "application/json; charset=utf-8",
                        "date": "Tue, 25 Jan 2022 11:19:50 GMT",
                        "expires": "-1",
                        "pragma": "no-cache",
                        "server": "Microsoft-IIS/10.0",
                        "x-aspnet-version": "4.0.30319",
                        "x-powered-by": "ASP.NET"
                    },
                    //url: base_url + '/client/orders/ManualOrders/delete-image',
                    url: 'http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData',
                    data: {
                            "username": "mansoor_4b459",
                            "password": "Mansoor1@3",
                            "consigneeName": "test",
                            "consigneeAddress": "test123",
                            "consigneeMobNo": "03330139993",
                            "consigneeEmail": "string",
                            "destinationCityName": "karachi",
                            "pieces": 0,
                            "weight": 0,
                            "codAmount": 0,
                            "custRefNo": "12345689",
                            "productDetails": "string",
                            "fragile": "string",
                            "service": "overnight",
                            "remarks": "string",
                            "insuranceValue": "string",
                            "locationID": "string",
                            "AccountNo": "string",
                            "InsertType": 0
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        console.log(e.messege);   
                        
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
</script>
@endsection