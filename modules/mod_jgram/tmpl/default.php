<?php
// No direct access
defined('_JEXEC') or die;
$userID 		= $params->get('user_id');
$accessToken 	= $params->get('access_token');
$classNames    = $params->get('class_name');
$tagName 	     = $params->get('tag_name');
$locationID 	= $params->get('location_id');
$restricted    = $params->get('restrict_id');
$limit		= $params->get('limit');
$hideProfile   = $params->get('profile_display');
$lightbox      = $params->get('lightbox');
$swiper        = $params->get('swiper');
$noBackground  = $params->get('noBackground');
$hideHashtag   = $params->get('hashtag_display');

if($swiper != "no"){
	$columns = "swiper";
	$padding = "swiper";
	$swiperClass  = "swiper";
}else{
	$columns = $params->get('columns');
	$padding = $params->get('padding');
	$swiperClass  = "";
}

if($noBackground != "yes"){
	$backgroundColor = $params->get('backgroundColor');
}else{
	$backgroundColor = 'transparent';
}

if($hideProfile != "yes"){
	$displayProfile = "block";
}else{
	$displayProfile = "none";
}

?>
<style>
	.jgram{
		background-color: <?php echo $backgroundColor; ?>;
	}

	.profile-pic, .user-info{
		display: <?php echo $displayProfile; ?>;
	}
</style>

<?php
echo '<div class="jgram-overlay">
<svg version="1.1" id="jgram-close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 475.2 475.2" style="enable-background:new 0 0 475.2 475.2;" xml:space="preserve">
<g>
	<g>
		<path d="M405.6,69.6C360.7,24.7,301.1,0,237.6,0s-123.1,24.7-168,69.6S0,174.1,0,237.6s24.7,123.1,69.6,168s104.5,69.6,168,69.6
			s123.1-24.7,168-69.6s69.6-104.5,69.6-168S450.5,114.5,405.6,69.6z M386.5,386.5c-39.8,39.8-92.7,61.7-148.9,61.7
			s-109.1-21.9-148.9-61.7c-82.1-82.1-82.1-215.7,0-297.8C128.5,48.9,181.4,27,237.6,27s109.1,21.9,148.9,61.7
			C468.6,170.8,468.6,304.4,386.5,386.5z"/>
		<path d="M342.3,132.9c-5.3-5.3-13.8-5.3-19.1,0l-85.6,85.6L152,132.9c-5.3-5.3-13.8-5.3-19.1,0c-5.3,5.3-5.3,13.8,0,19.1
			l85.6,85.6l-85.6,85.6c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4l85.6-85.6l85.6,85.6c2.6,2.6,6.1,4,9.5,4
			c3.5,0,6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1l-85.4-85.6l85.6-85.6C347.6,146.7,347.6,138.2,342.3,132.9z"/>
	</g>
</g>
</svg>


<svg version="1.1" id="jgram-prev" class="jgram-arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 254.248 254.248" style="enable-background:new 0 0 254.248 254.248;" xml:space="preserve">
<g>
	<g>
		<g>
			<path style="fill:#010002;" d="M200.994,4.716l0.064-0.064c-6.929-6.198-18.084-6.198-24.981-0.032L52.856,115.859
				c-6.929,6.198-6.929,16.241,0,22.407l0.636,0.381L176.077,249.6c6.897,6.198,18.052,6.198,24.981,0l0.127-0.191
				c3.305-2.86,5.403-6.865,5.403-11.283V16.157C206.588,11.613,204.427,7.576,200.994,4.716z"/>
		</g>
	</g>
</g>
</svg>

<svg version="1.1" id="jgram-next" class="jgram-arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="292.359px" height="292.359px" viewBox="0 0 292.359 292.359" style="enable-background:new 0 0 292.359 292.359;"
	 xml:space="preserve">
<g>
	<path d="M222.979,133.331L95.073,5.424C91.456,1.807,87.178,0,82.226,0c-4.952,0-9.233,1.807-12.85,5.424
		c-3.617,3.617-5.424,7.898-5.424,12.847v255.813c0,4.948,1.807,9.232,5.424,12.847c3.621,3.617,7.902,5.428,12.85,5.428
		c4.949,0,9.23-1.811,12.847-5.428l127.906-127.907c3.614-3.613,5.428-7.897,5.428-12.847
		C228.407,141.229,226.594,136.948,222.979,133.331z"/>
</g>
</svg>
</div>';
	switch ($params->get('get')) {
		case 'tagged':
			include 'tagged.php';
			$doc->addScript("modules/mod_jgram/js/tagged.js");
		break;
		case 'user':
			include 'user.php';
			$doc->addScript("modules/mod_jgram/js/user.js");
		break;
		case 'search':
			include 'search.php';
			$doc->addScript("modules/mod_jgram/js/search.js");
		break;
		case 'location':
			include 'location.php';
			$doc->addScript("modules/mod_jgram/js/location.js");
		break;
		default:
			echo '<h2>You need to make photostream selection</h2>';
		break;
	}
?>
