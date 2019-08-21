jQuery.noConflict();
jQuery(document).ready(function($){
     var accessToken 	= $('.jgram').attr('data-accessToken'),
	    userID 		 	= $('.jgram').attr('data-userID'),
	    limit 			= parseInt($('.jgram').attr('data-limit'));

     $.ajax({
      type: "GET",
      dataType: "jsonp",
      url: "https://api.instagram.com/v1/users/"+userID+"/?access_token="+accessToken,
      success: function(data) {
          $('.user-info .username').text(data.data.username);
          $('.user-info .fullname').text(data.data.full_name);
          $('.user-info .tagline').text(data.data.bio);
          $('.profile-pic img').attr('src', data.data.profile_picture);
          $('.user-info .website').text(data.data.website);
          $('.user-info .website').attr('href', data.data.website);
          $('.user-info .posts').html('<strong>' + data.data.counts.media + '</strong>' + ' Posts');
          $('.user-info .followers').html('<strong>' + data.data.counts.followed_by + '</strong>'  + ' Followers');
          $('.user-info .following').html('<strong>' + data.data.counts.follows + '</strong>'  + ' Following');
      }
     });

     //get user feed
     $.ajax({
      type: "GET",
      dataType: "jsonp",
      url: "https://api.instagram.com/v1/users/"+userID+"/media/recent/?access_token="+accessToken,
          success: function(data) {
          for (var i = 0; i < limit; i++) {
               if(data.data[i].type === "image"){
                         $(".jgram .user-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><img src=" + data.data[i].images.standard_resolution.url + " /></a><div>");
                    }

                    if(data.data[i].type === "video"){
                         $(".jgram .user-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><video controls><source src=" + data.data[i].videos.standard_resolution.url + " ></video></a><div>");
                    }
               }
          }
     });
});
