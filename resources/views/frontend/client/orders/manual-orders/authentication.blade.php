 
@extends('frontend.layouts.app')

@section('content')
    <style> 
        div {
        position: relative;
        overflow: hidden;
        }
        input[type=file] {
        position: absolute;
        font-size: 50px;
        opacity: 0; 
        right: 0;
        top: 0;
        }
        
         

        

    </style>
    <script>
    $(document).ready(function(){
        $("#exampleModalCenter").modal('show');
    });
</script>
    

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="container"> 
         
        
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        
        <form method="post" action="{{ route('Frontend.ManualOrders.create.guest.cookie') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
        @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Please enter your number to track your details</h5>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            
            
            
                <div class="form-group"> 
                    <span>Enter your parcel number</span>
                    <input type="number"  class="form-control" onkeydown="limit(this);" onkeyup="limit(this);" id="number" name="number" placeholder="03XXXXXXXXX" required>
                    @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
                </div> 
                 
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        
                
        </form> 
    </div>
  </div>
</div>

        
    </div>
    
  
    
     
  @endsection
