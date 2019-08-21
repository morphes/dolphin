//get instagam feed
jQuery.noConflict();
jQuery(document).ready(function($){
  var tagName 		= $('.jgram').attr('data-tag'),
      locationID 	= $('.jgram').attr('data-location'),
      accessToken 	= $('.jgram').attr('data-accessToken'),
	 userID 	     = $('.jgram').attr('data-userID'),
	 limit 	     = parseInt($('.jgram').attr('data-limit')),
      restricted    = $('.jgram').attr('data-restrict');

     $.ajax({
          type: "GET",
          dataType: "jsonp",
          url: "https://api.instagram.com/v1/locations/"+locationID+"/media/recent/?access_token="+accessToken,
          success: function(data) {
               for (var i = 0; i < limit; i++) {

                    var type  = data.data[i].type,
                        link  = data.data[i].link,
                        users = data.data[i].user.id;

                    function buildFeed(){
                         if(type === "image"){
                              $(".location-feed").append("<div class='jgram-img'><a href='"+link+"' target='_blank'><img src=" + data.data[i].images.standard_resolution.url+ " /></a></div>");
                         }

                         if(type === "video"){
                              $(".location-feed").append("<div class='jgram-img'><a href='"+link+"' target='_blank'><video controls><source src=" + data.data[i].videos.standard_resolution.url+ " ></video></a></div>");
                         }
                    }

                    if(restricted != "yes"){
                              buildFeed();
                    }else{
                         if(users === userID){
                              buildFeed();
                         }
                    }

               }
          }
     });
});
