
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 <head>
<script type="text/javascript"> 
    var base_url = '<?php echo e(url('/')); ?>';
       

        

        

    </script>  
</head>
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h4>Edit / {{$inventory->id}}</h4> 
            </div>
        </div>
    </div>
    
    <div class="container">  

        <form action="{{ route('inventory.update',$inventory->id) }}" id="update_form"  enctype="multipart/form-data" method="post">
            @csrf
            
            <input type="hidden" value="{{$inventory->id}}" name="inventory_id" id="inventory_id">
            <div class="container">
                <div class="row"> 
                    <div class="row">
                        
                        <?php 
                        // $count=1;
                        ?>
                        
                        <!--@if(!empty($ManualOrder->images))-->
                        <!--    @foreach(explode('|', $ManualOrder->images) as $image)  -->
                        <!--    <div class="form-group col-sm">-->
                        <!--        <div class="card" id="imagebox{{$count}}" style="max-width: 200px;">-->
                        <!--            <img class="card-img-top" src="{{asset($image)}}" alt="Card image cap" >-->
                        <!--            <div class="card-body">-->
                        <!--                <button type="button"  onclick="delete_image('{{$image}}','imagebox{{$count}}')" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal">-->
                        <!--                delete-->
                        <!--                </button> -->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--        <?php 
                        // $count++;
                        ?> 
                        <!--    @endforeach-->
                        <!--@endif-->
                            
                        
                        
                    </div>   
                    
                    <div class="form-group">
                        <input type="file" name="images[]" id="images" multiple/>
                        <!--<div class="file btn btn-lg btn-secondary">Add new-->
                            <input type="hidden" name="images_path" id="images_path" />
                            
                        <!--</div>-->
                        <!--@if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif-->
                    </div>
                    
                        
                    <div class="row">
                        <h5>Product Detail <hr></h5>
            
                        <div class="form-group col-auto">
                            <label for="SKU">SKU</label>
                            <input type="text" class="form-control @if($errors->get('sku')) is-invalid @endif" value="{{old('sku')}}@if(isset($inventory)){{$inventory->products->sku}}@endif" id="sku"  value="{{old('sku')}} @if(isset($inventory)) {{$inventory->products->sku}}  @endif" name="sku" placeholder="Sku" required>
                            @if($errors->get('sku')) <small id="sku_error" class="form-text text-danger"></small>{{$errors->first('sku')}} @endif
                        </div> 
            
                        <div class="form-group col-auto">
                            <label for="First Name">Slug</label>
                            <input type="text" class="form-control @if($errors->get('slug')) is-invalid @endif" value="{{old('slug')}}@if(isset($inventory)){{$inventory->products->slug}}@endif" id="slug"  value="{{old('slug')}} @if(isset($inventory)) {{$inventory->products->slug}}  @endif" name="slug" placeholder="Slug" >
                            @if($errors->get('slug')) <small id="last_name_error" class="form-text text-danger"></small>{{$errors->first('slug')}} @endif
                        </div> 
                        
                        <div class="form-group col-auto">
                            <label for="address">Category</label>
                            <select class="form-control " id="category_id"  name="category_id" required>
                                <option value="">Select City</option>
                                @for($i=0 ; $i < sizeof($categories); $i++)
                                 
                                    <option value="{{$categories[$i]->id}}" {{ ($categories[$i]->id == $inventory->products->category_id) ? 'selected="selected"' : '' }}>{{$categories[$i]->name}}</option>
                                    
                                @endfor
                                
                            </select> 
                            <small id="category_id_error" class="form-text text-danger">@if($errors->get('category_id')) {{$errors->first('category_id')}} @endif</small>
                        </div>    
            
                        <div class="form-group col-auto">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @if($errors->get('name')) is-invalid @endif" value="{{old('name')}}@if(isset($inventory)){{$inventory->products->name}}@endif" id="product_name"  name="product_name" placeholder="Product Name" required>
                            @if($errors->get('name')) <small id="name_error" class="form-text text-danger">{{$errors->first('name')}} </small>@endif
                        </div>    
            
                        <div class="form-group col-auto">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control @if($errors->get('weight')) is-invalid @endif" value="{{old('weight')}}@if(isset($inventory)){{$inventory->products->weight}}@endif" id="weight"  name="weight" placeholder="Product Weight" >
                            @if($errors->get('weight')) <small id="weight_error" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
                        </div> 
                        
                        <div class="form-group col-auto">
                            <label for="address">Weight Type</label>
                            <select class="form-control " id="weight_type"  name="weight_type" >
                                <option value="">Select City</option>
                                <option value="ml" @if($inventory->products->weight_type === "ml") selected @endif>ml</option>
                                <option value="mg" @if($inventory->products->weight_type === "mg") selected @endif>mg</option>
                            </select> 
                            <small id="city_error" class="form-text text-danger">@if($errors->get('weight_type')) {{$errors->first('weight_type')}} @endif</small>
                        </div>   
            
                        <div class="form-group col-auto">
                            <label for="sale_price">Sale Price</label>
                            <input type="number" class="form-control @if($errors->get('sale_price')) is-invalid @endif" value="{{old('sale_price')}}@if(isset($inventory)){{$inventory->products->sale_price}}@endif" id="sale_price"  name="sale_price" placeholder="Sale Price" >
                            @if($errors->get('sale_price')) <small id="sale_price_error" class="form-text text-danger">{{$errors->first('sale_price')}} </small>@endif
                        </div>  
            
                        <div class="form-group col-auto">
                            <label for="discount_price">Dicount Price</label>
                            <input type="number" class="form-control @if($errors->get('discount_price')) is-invalid @endif" value="{{old('discount_price')}}@if(isset($inventory)){{$inventory->products->discount_price}}@endif" id="discount_price"  name="discount_price" placeholder="Sale Price" >
                            @if($errors->get('discount_price')) <small id="discount_price_error" class="form-text text-danger">{{$errors->first('discount_price')}} </small>@endif
                        </div> 
                         
                    </div>
                    
                    <div class="row">
                        <h5>Inventory Detail <hr></h5>  
            
                        <div class="form-group col-auto">
                            <label for="customer_id">Customer ID</label>
                            <input type="number" class="form-control @if($errors->get('customer_id')) is-invalid @endif" value="{{old('customer_id')}}@if(isset($inventory)){{$inventory->customer_id}}@endif" id="customer_id"  name="customer_id" placeholder="Customer ID" >
                            @if($errors->get('customer_id')) <small id="customer_id_error" class="form-text text-danger">{{$errors->first('customer_id')}} </small>@endif
                        </div>   
            
                        <div class="form-group col-auto">
                            <label for="reference_id">Reference id (order id / stock id)</label>
                            <input type="number" class="form-control @if($errors->get('reference_id')) is-invalid @endif" value="{{old('reference_id')}}@if(isset($inventory)){{$inventory->reference_id}}@endif" id="reference_id"  name="reference_id" placeholder="reference_id" >
                            @if($errors->get('reference_id')) <small id="reference_id_error" class="form-text text-danger">{{$errors->first('reference_id')}} </small>@endif
                        </div> 
            
                        
                        <div class="form-group col-auto">
                            <label for="stock_type">Stock Type</label>
                            <select class="form-control " id="stock_type"  name="stock_type" >
                                <option value="">Select Stock Type</option>
                                <option value="StockAdjestment" @if($inventory->stock_type === "StockAdjestment") selected @endif">Stock Adjestment</option>
                                <option value="Order" @if($inventory->stock_type === "Order") selected @endif>Order</option>
                            </select> 
                            <small id="stock_type_error" class="form-text text-danger">@if($errors->get('stock_type')) {{$errors->first('stock_type')}} @endif</small>
                        </div> 
            
                        <div class="form-group col-auto">
                            <label for="cost">Cost</label>
                            <input type="number" class="form-control @if($errors->get('cost')) is-invalid @endif" value="{{old('cost')}}@if(isset($inventory)){{$inventory->cost}}@endif" id="inventory_cost"  name="inventory_cost" placeholder="Cost" >
                            @if($errors->get('cost')) <small id="cost_error" class="form-text text-danger">{{$errors->first('cost')}} </small>@endif
                        </div>  
            
                        <div class="form-group col-auto">
                            <label for="cost">Sale</label>
                            <input type="number" class="form-control @if($errors->get('sale')) is-invalid @endif" value="{{old('sale')}}@if(isset($inventory)){{$inventory->sale}}@endif" id="inventory_sale"  name="inventory_sale" placeholder="sale" >
                            @if($errors->get('sale')) <small id="sale_error" class="form-text text-danger">{{$errors->first('sale')}} </small>@endif
                        </div>  
            
                        
                    </div>
                    <div class="form-group col-auto">
                        <input type="submit" name="update_inventory" class="btn btn-primary" id="update_inventory" value="save">
                        </div>
                </div> 
            </div>
           
                
        </form> 

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this image?</p>
                </div>
                <div class="modal-footer"> 
                    <button type="button" id="delete_image" class="btn btn-primary" >Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    
     
  @endsection
