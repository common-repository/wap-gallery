(function($){
	
	"use strict";
	
	var lightbox = $('#wap-lightbox');
	var lightbox_image = $('#wap-lightbox > img');
	var images = $('.wap-gallery-item');
	
	$(images).on("click", function(){
		
		var src = $(this).data("large");
				
		$(lightbox_image).attr("src", src );
		
		$(lightbox).show();
		
		$(lightbox_image).delay(3000).css("opacity", 1);
		
	});
	
	$(lightbox).on("click", function(){
		$(lightbox).hide();
	});
	
})(jQuery);