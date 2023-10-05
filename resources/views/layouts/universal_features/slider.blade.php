<div class="modal" id="UniversalImageModal" tabindex="-2" role="dialog" aria-labelledby="Image slider">
    <div class="modal-dialog modal-dialog-centered modal-md"  role="document">
        <div class="modal-content"  > 
            <div class="modal-footer">
                <button type="button" id="closeuniversalimagemodal" onclick="removeElementsByClass('modal-backdrop');" class="close">Close</button> 
            </div>
          <div class="modal-body">
            
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" id="universal_carousel_slider_inner_images">
            
                </a>
            </div>
          </div>  
    
    
        </div>
      </div>
    </div>
</div>

<div class="modal" id="UniversalImageBoxModal" tabindex="-2" role="dialog" aria-labelledby="Image slider">
    <div class="modal-dialog modal-dialog-centered modal-xl"  role="document">
        <div class="modal-content"  > 
            <div class="modal-footer">
                <button type="button"  id="UniversalImageBoxModalClose" class="close">Close</button> 
            </div>
            <div class="modal-body">
            
                <div class="table-container" id="table-container-data" style="overflow-x:auto;"> 
                
                    <div class="col-sm-12 row" id="universal_carousel_box_inner_images"> 
                         
                            
                        
                    </div> 
                    
                </div>
            </div>  
    
    
        </div>
    </div>
</div> 



<script   type="application/javascript">
 var order_id_for_demand = '';
    function send_product_demand(id)
    {
        var base_url = '<?php echo e(url('/')); ?>';
        var img_src = $('#image_src_id_'+id).attr('title');
        var category = $('#cetogery_image_id_'+id).val();
        // var route= base_url+'/admin/product/demand/create/'+category+'/'+img_src;
        // window.open(route, '_blank');
        console.log(category+'   |   '+img_src);
        $.ajax({
              url: base_url+'/admin/product/demand/create',
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type:"POST",
              dataType: 'json',
              data:{
                img_src:img_src,
                category:category,
                order_id:order_id_for_demand,
              },
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
        
        // alert(category);
        
        // alert(image);
    }
    
    function UniversalImagesBoxes(image_index,images,order_id)
    {
        order_id_for_demand = order_id;
        var UCSIM = document.getElementById('universal_carousel_box_inner_images');
        const images_array = images.split("|");
        var active_image_index = '';
        var slider_imgage_src = "";
        for(var i=0; i<images_array.length; i++)
        {
            if(image_index == i)
            {
                active_image_index = 'active';
            }
            else
            {
                active_image_index = '';
            }
            
            slider_imgage_src +='<div class="col-sm-4">'; 
                slider_imgage_src +='<div class="card col-sm-12" id="imagebox" >';
                
                    
                    slider_imgage_src+='<div class="card-body" style="height:300px">';
                    
                        slider_imgage_src +='<img class="card-img-top "  style="max-height: 260px;" title="'+images_array[i]+'" id="image_src_id_'+i+'" src="{{  url('') }}/'+images_array[i]+'" alt="Card image cap" >';
            
                    slider_imgage_src +='</div>';
                    
                    slider_imgage_src+='<div class="card-footer">';
                    
                        slider_imgage_src +='<form class="form-inline">';
                    
                            slider_imgage_src +='<div class="form-group">';
                            
                                slider_imgage_src +='<div class="input-group">';
                                    slider_imgage_src +='<select class="custom-select" style="max-width:150px" id="cetogery_image_id_'+i+'">';
                                        slider_imgage_src +='<option value="">Select Category</option>';
                                        @if(isset($catgories))
                                        
                                        @foreach($catgories as $catgory)
                                        slider_imgage_src +='<option value="{{$catgory->name}}">{{$catgory->name}}</option>';
                                            @endforeach
                                            
                                        @endif
                            
                                    slider_imgage_src +='</select>';
                                slider_imgage_src +='</div>';
                                
                                slider_imgage_src +='<div class="input-group-append">';
                                    slider_imgage_src +='<button class="btn btn-primary" type="button" onclick="send_product_demand('+i+')">Send Demand</button>';
                                slider_imgage_src +='</div>'; 
                                
                            slider_imgage_src += '</div>';          
                        slider_imgage_src += '</form>';      
                    
                    slider_imgage_src+='</div>';
                slider_imgage_src +='</div>';
            slider_imgage_src +='</div> ';
            
        }
        
 
        
        UCSIM.innerHTML = slider_imgage_src;
        // console.log(slider_imgage_src);
        $('#UniversalImageBoxModal').modal('show');
    }
    
    function UniversalImagesSlider(image_index,images)
    {
        var UCSIM = document.getElementById('universal_carousel_slider_inner_images');
        const images_array = images.split("|");
        var active_image_index = '';
        var slider_imgage_src = "";
        for(var i=0; i<images_array.length; i++)
        {
            if(image_index == i)
            {
                active_image_index = 'active';
            }
            else
            {
                active_image_index = '';
            }
            
            slider_imgage_src += '<div class="carousel-item '+active_image_index+'">';
            slider_imgage_src += '<img class="d-block w-100" title="'+images_array[i]+'" id="image_src_id_'+i+'" src="{{  url('') }}/'+images_array[i]+'" alt="">';
            
                
            slider_imgage_src += '<div class="form-group">';
            slider_imgage_src += '<div class="input-group">';
            slider_imgage_src += '<select class="custom-select" id="cetogery_image_id_'+i+'">';
            slider_imgage_src += '<option value=""></option>';
            @if(isset($catgories))
                @foreach($catgories as $catgory)
                slider_imgage_src += '<option value="{{$catgory->name}}">{{$catgory->name}}</option>';
                @endforeach 
            @endif
            
            slider_imgage_src += '</select>';
            slider_imgage_src += '<div class="input-group-append">';
            slider_imgage_src += '<button class="btn btn-primary" type="button" onclick="send_product_demand('+i+')">Send Demand</button>';
            slider_imgage_src += '</div>'; 
            slider_imgage_src += '</div>';
            
        }
        
        slider_imgage_src += '<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">';
        slider_imgage_src += '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
        slider_imgage_src += '<span class="sr-only">Previous</span>';
        slider_imgage_src += ' </a>';
        
        slider_imgage_src += '<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">';
        slider_imgage_src += '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
        slider_imgage_src += '<span class="sr-only">Next</span>';
        slider_imgage_src += ' </a>';
        
        UCSIM.innerHTML = slider_imgage_src;
        console.log(slider_imgage_src);
        $('#UniversalImageModal').modal('show');
    }
 
    $(document).ready(function() {
        $("#closeuniversalimagemodal").click(function(){
            $('#UniversalImageModal').modal('hide');
            // $('body').attr("class", ""); 
            
        });
        $("#UniversalImageBoxModalClose").click(function(){
            $('#UniversalImageBoxModal').modal('hide');
            // $('body').attr("class", ""); 
            
        });
    });
    
</script>


