
@extends('layouts.'.Auth::getDefaultDriver())



@section('content') 

<script  type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
function delete_image(id)
{ 
        $.ajax({
              url: base_url+'/admin/product/demand/destroy/'+id,
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type:"POST",
              dataType: 'json', 
              success:function(response){
                console.log(response);
                //console.log(response);
              },
              error: function(response) {
                    console.log(response); 
                    // $("body").removeClass("loading");
                // $('#nameErrorMsg').text(response.responseJSON.errors.name);
                // $('#emailErrorMsg').text(response.responseJSON.errors.email);
                // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
                // $('#messageErrorMsg').text(response.responseJSON.errors.message);
              },
          });
    
}
</script>



<div class="table-container" id="table-container-data" style="overflow-x:auto;"> 
 
    <div class="col-sm-12 row"> 
         
        @foreach($list as $lists)
            <div class="col-sm-3">
                <div class="card " id="imagebox" >
                    <img class="card-img-top " src="{{asset($lists->image)}}" alt="Card image cap" >
                    <div class="card-body">
                        <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                        <a onclick="delete_image('{{$lists->id}}')" class="btn btn-primary">Remove Demand</a>
                    </div>
                </div>
            </div>
             
            @endforeach 
        
                </div> 
</div>
{!! $list->appends(Request::all())->links() !!} 


@endsection