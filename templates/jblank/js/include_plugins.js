$(document).ready(function() {
	/*home slider*/
	$(window).load(function() {
		$('.flexslider').flexslider({
			animation: "slide",
			slideshowSpeed: 7000, 
			manualControls: '.partners li a'
		});
	});
	/*home slider*/
	/*scroll*/
	
	$(window).load(function() {
		$('.scroll_news').jScrollPane();
	});
	$(window).resize(function(event) {
		$('.scroll_news').jScrollPane();
	});
	/*scroll*/
	/*owlCarousel*/
	$(window).load(function() {
		$('.owl-carousel').owlCarousel({
		    loop:true,
		    margin:0,
		    nav:true,
		    responsive:{
		        0:{
		            items:2
		        },
		         680:{
		            items:4
		        },
		        800:{
		            items:3
		        },
		        1000:{
		            items:5
		        }
		    }
		})
	});
	/*owlCarousel*/
});





