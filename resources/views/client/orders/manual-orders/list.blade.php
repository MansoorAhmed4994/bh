@extends('layouts.app')

@section('content') 
<script>
    $( document ).ready(function() { 
                $('.pop').on('click', function() {
                    // alert($(this).attr('src'));
                    $('.imagepreview').attr('src', $(this).attr('src'));
                    $('#imagemodal').modal('show');   
                });   
    });
    
    
    
    function get_checked_values()
    {
        var array = [];
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) 
        {
            array.push(checkboxes[i].value)
        }
        document.getElementById('order_ids').value = array;
        //alert(array);
    }
</script>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" data-dismiss="modal">
    <div class="modal-content"  >              
      <div class="modal-body">
        <!-- <button type="button" class="close" data-dismiss="modal">Close</button> -->
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>  


    </div>
  </div>
</div>

<nav class="navbar navbar-light bg-light">
  <form class="form-inline" method="post" action="{{ route('ManualOrders.search.order') }}">
      @csrf
    <div class="form-group">
        <input class="form-control mr-sm-2" type="search" name="search_text" placeholder="Search" aria-label="Search">
    </div>
    
    <div class="form-group">
        <select class="form-select" aria-label="Default select example" name="order_status">
          <option selected value ="">Select Order Status</option>
          <option value="">All</option>
          <option value="prepaired">Prepaired</option>
          <option value="confirmed">Confirmed</option>
          <option value="dispatched">Dispatched</option>
          <option value="print">Print</option>
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </div>
    
    
  </form>
  
    <form class="form-inline" method="post" action="{{ route('ManualOrders.order.action') }}">
      @csrf
    <input type="hidden" name="order_ids" id="order_ids">
    <div class="form-group">
        <select class="form-select" aria-label="Default select example" name="order_action" required>
          <option selected >Select Action</option>
          <option value="print">Print</option>
          <option value="prepare">Prepare</option>
          <option value="confirmed">Confirmed</option>
          <option value="dispatched">Dispatched</option>
          <option value="print">Print</option>
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Submit</button>
    </div>
   </form>   
</nav>

<div style="overflow-x:auto;"> 
    <table class="table table-bordered">
        <thead>
            <tr> 
                <th scope="col"></th>
                <th scope="col">#</th>
                <th scope="col">Edit</th>
                <th scope="col">Print</th>
                <th scope="col">Prepared</th>
                <th scope="col">Confirmed</th>
                <th scope="col">Dispatched</th>
                <th scope="col">deleted</th>
                <th scope="col">Customer ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Number</th>
                <th scope="col">Description</th>
                <th scope="col">Ord. Location</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Images</th>
                <th scope="col">Total Pieces</th>
                <th scope="col">Ord.paid Date</th>
                <th scope="col">Ord. tatus</th>
            </tr>
        </thead>
        <tbody>  
            @foreach($list as $lists)
            
            <tr style="background-color:@if($lists->status == 'deleted')#f99c9c @elseif($lists->status == 'prepaired') #b7b8b9 @elseif($lists->status == 'confirmed') #91c6ff @elseif($lists->status == 'dispatched') #84f39c @elseif($lists->status == 'deleted') #e77272 @else #f9df90 @endif">
                <th ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></th>
                <th scope="row">{{$lists->customers_id}}</th>            
                <th><a type="button" href="{{route('ManualOrders.edit',$lists->id)}}" class="btn btn-warning">Edit</a></th>             
                <th><a type="button" href="{{route('ManualOrders.show',$lists->id)}}" class="btn btn-warning">view</a></th> 
                <th><a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="btn btn-info">Print Slip</a></th>  
                <th><a type="button" href="{{route('ManualOrders.order.status',['prepared',$lists->id])}}" class="btn btn-secondary">Prepare</a></th> 
                <th><a type="button" href="{{route('ManualOrders.order.status',['confirmed',$lists->id])}}" class="btn btn-primary">Confirmed</a></th> 
                <th><a type="button" href="{{route('ManualOrders.order.status',['dispatched',$lists->id])}}" class="btn btn-success">Dispatch</a></th> 
                <th><form method="post" class="delete_form" action="{{route('ManualOrders.destroy',$lists->id)}}">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form></th>
                <th>{{$lists->customers_id}}</th>
                <th>{{$lists->first_name}}</th>
                <th>{{$lists->last_name}}</th>
                <th>{{$lists->number}}</th>
                <th>{{$lists->description}}</th>
                <th>{{$lists->order_delivery_location}}</th>
                <th>{{$lists->address}}</th>
                <th>{{$lists->price}}</th>
                <th style="display: flex;">
                    @if(!empty($lists->images))
                        @foreach(explode('|', $lists->images) as $image)   
                        <img class="pop rounded float-left" style="margin-right: 5px;" src="{{asset($image)}}" alt="Card image cap" width="50">
                        @endforeach
                    @endif
                </th>
                <th>{{$lists->total_pieces}}</th>
                <th>{{$lists->date_order_paid}}</th>
                <th>{{$lists->status}}</th>
            </tr>
            @endforeach
        </tbody>
        
    </table>
</div>
{{ $list->links() }}
@endsection