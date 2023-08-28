<div class="modal" id="UniversalImageModal" tabindex="-2" role="dialog" aria-labelledby="Image slider">
    <div class="modal-dialog modal-dialog-centered modal-xl"  role="document">
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



<script   type="application/javascript">
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
            slider_imgage_src += '<img class="d-block w-100" src="{{  url('') }}/'+images_array[i]+'" alt="">';
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
    
});
    
</script>


