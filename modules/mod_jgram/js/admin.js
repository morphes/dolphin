jQuery.noConflict();
jQuery(document).ready(function($){

     $("#jgram-get-locations").on('click', function(e){
          e.preventDefault();
          var  lat = $('#jgram-latitude').val(),
               lng = $('#jgram-longitude').val(),
               akt = $('#jgram-accesstoken').val(),
               url = "https://api.instagram.com/v1/locations/search?lat="+lat+"&lng="+lng+"&access_token="+akt;

          $.ajax({
               type: "GET",
               dataType: "jsonp",
               url: url,
               success: function(data) {

                    for (var i = 0; i < data.data.length; i++) {
                         $('#jgram-location').append("<p><span class='number'>"+i+"</span>"+data.data[i].name+" - Location ID: "+data.data[i].id+"</p>");
                    }
               }
          });
     });
});
