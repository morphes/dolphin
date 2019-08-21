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

<?php
echo '';
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
