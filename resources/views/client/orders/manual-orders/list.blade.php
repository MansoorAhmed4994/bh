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

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Number</th>
            <th scope="col">Address</th>
            <th scope="col">Price</th>
            <th scope="col">Images</th>
            <th scope="col">Address</th>
            <th scope="col">Address</th>
        </tr>
    </thead>
    <tbody> 
        @foreach($list as $lists)
        <tr>
            <th scope="row">{{$lists->id}}</th>            
            <th><a type="button" href="{{route('ManualOrders.edit',$lists->id)}}" class="btn btn-warning">Edit</a></th>            
            <th>{{$lists->id}}</th>
            <th>{{$lists->id}}</th>
            <th>{{$lists->firstname}}</th>
            <th>{{$lists->number}}</th>
            <th>{{$lists->order_delivery_location}}</th>
            <th>{{$lists->address1}}</th>
            <th>{{$lists->price}}</th>
            <th>
                @if(!empty($lists->images))
                    @foreach(explode('|', $lists->images) as $image)   
                    <img class="pop rounded float-left" src="{{asset($image)}}" alt="Card image cap" width="100">
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
{{ $list->links() }}
@endsection