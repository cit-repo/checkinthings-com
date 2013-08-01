/*************************************************
      Bgstretch Simplae
      @author Fabio Mangolini
      http://www.responsivewebmobile.com
**************************************************/
(function($) {
	$.Bgstretch = function(options) {
		var settings = $.extend({
            // These are the defaults.
            image: './img/bg.jpg', //path and background image name
        }, options );


	//viewport dimensions on page load
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();

	//main background container
	var origDiv = '#bgstretch'; 
	var origImg = '#bgstretch img';

	//initialize the body with no margin and padding
	$('body').css({'margin': 0});

	//append to the body the div containing the image
	$('body').append('<div id="bgstretch" style="z-index: -1; position: fixed; top: 0; left: 0; margin:0; padding: 0; width: '+windowWidth+'px; height: '+windowHeight+'px;"><img src="'+settings.image+'" /></div>');

	//adapt image to window dimension on first load
	resize();

	//on every resize call the resize() function to recalculate the image ratio and scale it to fit the window dimensions
	$(window).bind('resize', function() {
		resize();
	});

	//resize the background image calculating the right proportions
	function resize() {
		var imgWidth = $(origImg).width(); 
		var imgHeight = $(origImg).height(); 

		var ratio = imgHeight/imgWidth;
		var winWidth = $(window).width(); 
		var winHeight = $(window).height(); 
		var winRatio = winHeight/winWidth;  

		if (winRatio > ratio) { 
			$(origDiv).height(winHeight); 
			$(origDiv).width(winHeight / ratio); 
			$(origImg).height(winHeight); 
			$(origImg).width(winHeight / ratio); 
		} else { 
			$(origDiv).width(winWidth); 
			$(origDiv).height(winWidth * ratio); 
			$(origImg).width(winWidth); 
			$(origImg).height(winWidth * ratio); 
		}
	}
};
})(jQuery);