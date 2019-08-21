jQuery.noConflict();
jQuery(document).ready(function($){

	//populate jgram overlay
	function jgramOverlay(){
	    if($('.jgram').attr('data-lightbox') != "no"){
		    //stop link redirect
		    	$('body').on('click', '.jgram a', function(e){
		    		e.preventDefault();
		    	});

			$('body').on('click', '.jgram .jgram-img', function (){
				$('body').addClass('jgram-body-active');
		        	$('.jgram-overlay').fadeIn('fast');
		        	$(this).addClass('jgram-active');
		        	$(this).clone().appendTo('.jgram-overlay');
	    		});
		}
	}
	jgramOverlay();

	function jgramNext(){
		$('body').on('click', '#jgram-next', function(){
			$('.jgram-overlay .jgram-img').remove();
			$('.jgram .jgram-active').next().addClass('jgram-active');
			$('.jgram .jgram-active').prev().removeClass('jgram-active');
			$('.jgram .jgram-active').clone().appendTo('.jgram-overlay');
		});
	}
	jgramNext();

	function jgramPrev(){
		$('body').on('click', '#jgram-prev', function(e){
			e.preventDefault();
			$('.jgram-overlay .jgram-img').remove();
			$('.jgram .jgram-active').prev().addClass('jgram-active');
			$('.jgram .jgram-active').next().removeClass('jgram-active');
			$('.jgram .jgram-active').clone().appendTo('.jgram-overlay');
		});
	}
	jgramPrev();

	function jgramClose(){
		$('#jgram-close').on('click', function(e){
			e.preventDefault();
			$('.jgram div').removeClass('jgram-active');
        		$('body').removeClass('jgram-body-active');
        		$('.jgram-overlay').fadeOut(function(){
        		$('.jgram-overlay .jgram-img').remove();
        	});
        });
	}
	jgramClose();

});
