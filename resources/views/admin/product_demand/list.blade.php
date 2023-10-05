
@extends('layouts.'.Auth::getDefaultDriver())



@section('content') 

<style>
.card-box-custom{
    background: white;
    border: 1px solid grey;
    border-radius: 15px;
    margin: 5px;
}
</style>
<script  type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
function delete_image(id,row)
{ 
    $("body").addClass("loading");
        $.ajax({
              url: base_url+'/admin/product/demand/destroy/'+id,
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type:"POST",
              dataType: 'json', 
              success:function(response){
                  if (typeof response.success !== 'undefined') 
                  {
                      $('#col_'+row).remove();
                      
                  }
                  $("body").removeClass("loading");
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
         <?php $row=0;?>
        @foreach($list as $lists)
            <div class="col-sm-3" id="col_{{$row}}">
                <div class="card card-box-custom" id="imagebox" >
                    
                    <div class="card-body">
                        <img class="card-img-top " src="{{asset($lists->image)}}" alt="Card image cap" >
                        <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                        
                    </div>
                    <div class="card-footer">
                        <p class="card-text">Order ID: {{$lists->ref_id}}</p>
                        <a onclick="delete_image('{{$lists->id}}',{{$row}})" class="btn btn-primary">Remove Demand</a>
                    </div>
                </div>
            </div>
             <?php $row++;?>
            @endforeach 
        
    </div> 
</div>
{!! $list->appends(Request::all())->links() !!} 


@endsection