 
@extends('layouts.app')

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

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="container"> 
         
        

        <form method="post" action="{{ route('ManualOrders.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
            @csrf

            <div class="form-group">
                <div class="file btn btn-lg btn-primary">Upload
                    <input type="file" name="images[]" multiple  required/>
                    
                </div>
                @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="First Name">Name</label>
                <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="First Name" required>
                @if($errors->get('first_name')) <small id="first_name_error" class="form-text text-danger"></small>{{$errors->first('first_name')}} @endif
            </div> 

            <div class="form-group">
                <label for="Number">Number</label>
                <input type="text" class="form-control" id="number"  name="number" placeholder="number Number" required>
                @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
            </div>  

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address1"  name="address1" placeholder="address" required></textarea>
                <small id="address1_error" class="form-text text-danger">@if($errors->get('address1')) {{$errors->first('address1')}} @endif</small>
            </div> 

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
                
        </form> 
    </div>
    
     
  @endsection
