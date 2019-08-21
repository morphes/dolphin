$('.clone_news li').each(function(index, el) {
	var html_li = $(this).html();
	$('.sec-works-slider').prepend('<div class="sec-works-slide">' + html_li + '</div>')
});

$(document).ready(function() {
/********************** tabs ************* ********/
	$('.tabs_ul_swith > li:first() span').addClass('tab_li_active');
	$('.tabs_ul_content > li:first()').show();


	$('.tabs_ul_swith > li').click(function(index){
		var index_tab = $(this).index();
		$('.tabs_ul_content > li').hide();
		$('.tabs_ul_swith > li span').removeClass('tab_li_active');
		$(this).find("span").addClass('tab_li_active');

		$('.tabs_ul_content > li').each(function(index) {
			var index_li = $(this).index();
			if(index_tab == index_li){
				$(this).show();
			}
		});
	});
/********************** tabs ************* ********/
/********************** menu ************* ********/
	$('.mobile_menu').click(function(event) {
		$('.contain_nav').fadeIn(400);
		$('nav').addClass('position_static');
	});
	$('.close_menu').click(function(event) {
		$('.contain_nav').fadeOut(400);
		$('nav').removeClass('position_static');
	});
/********************** menu ************* ********/
/********************** form ************* ********/
	$('form.dispatch .text_input input').focus(function(event) {
		$(this).parents('form').addClass('fomr_active');
	});
	$('form.dispatch .text_input input').focusout(function(event) {
		$(this).parents('form').removeClass('fomr_active');
	});
/********************** form ************* ********/
/********************** video *********************/
	var clone_iframe = $('.ul_video_ul li').first().find('iframe').clone();
	$('.block_video_fuul').append(clone_iframe);
	function offser_video() {
		var foset_var = $('.video_select').offset();
		foset_var = foset_var.top;
		$('html,body').animate({"scrollTop":foset_var + 20}, 0);
		return false;
	}
	$('.ul_video_ul li').click(function(event) {
		$('.ul_video_ul li').removeClass('active_video');
		$(this).addClass('active_video');
		var zm = $(this).find('iframe').clone();
		$('.block_video_fuul > *').remove();
		$('.block_video_fuul').append(zm);
		offser_video()
	});
/********************** video *********************/
/********************** foto *********************/
	$('.foto_contain .item_foto').click(function(event) {

		$('.foto_contain ul li a').removeClass('fancybox');

		$(this).find('ul a').addClass('fancybox');
	});
	/********************** foto *********************/
	/********************** form *********************/
	$('.form_reserve').submit(function( event ) {
		$('.reserv_modal').fadeIn(200);
		$('.reserv_window').slideDown(400);
		event.preventDefault();
	});

	$('.close_window').click(function(event) {
		$('.reserv_window').slideUp(400);
		setTimeout(function(){
			$('.reserv_modal').fadeOut(200);
		}, 200);
	});
/********************** form *********************/

/*video youtube*/
	$('.video_rm').each(function(index, el) {
		var video = $(this).attr("src");
		$(this).attr("src","");
		$(this).attr("src",video + "&showinfo=0");
	});

	$('.play').click(function(event) {
		/*close video*/
		$('.video_rm').each(function(index, el) {
			var zm = $(this).attr('src');
			var zm_2 = $(this).attr('src', zm + '?showinfo=0');
		});
		/*close video*/

		$('.play').removeClass('active_video');
		$(this).addClass('active_video');
		var $video = $(this).find('.video_rm'),
		src = $video.attr('src');
		$video.attr('src', src + '&autoplay=1');
	});
	/*click after*/
	$(function(){
		$(document).click(function(event) {
			if ($(event.target).closest(".play").length) return;
			
			/*close video*/
			$('.play').each(function(index, el) {
				var has_cl = $(this).hasClass('active_video');
				if(has_cl == true){
					var zm = $(this).find('.video_rm').attr('src');
					var zm_2 = $(this).find('.video_rm').attr('src', zm + '?showinfo=0');
				}
				$(this).removeClass('active_video');
			});
			/*close video*/
			event.stopPropagation();
		});
	});
	/*click after*/
/*video youtube*/









});