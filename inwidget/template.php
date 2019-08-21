<?php 
if(!$inWidget) die('inWidget object was not initialised.');
if(!is_object($inWidget->data)) die('<b style="color:red;">Cache file contains plain text:</b><br />'.$inWidget->data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html style="background-color:#062759;" xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title>inWidget - free Instagram widget for your site!</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="<?php echo $inWidget->langName; ?>" />
		<meta http-equiv="content-style-type" content="text/css2" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="stylesheet" href="http://dolphinevpatoria.ru/cache/jblank/minify-b81e9d6089ef2dfe46ce52f2e4bd8b2d.css?103" type="text/css" />
		<script src="http://dolphinevpatoria.ru/templates/jblank/js/jquery-2.1.4.min.js?179" type="text/javascript"></script>
		<script src="http://dolphinevpatoria.ru/templates/jblank/js/jquery.flexslider-min.js?179" type="text/javascript"></script>
		<script src="http://dolphinevpatoria.ru/templates/jblank/js/include_plugins.js?178" type="text/javascript"></script>
 		<script src="http://dolphinevpatoria.ru/templates/jblank/js/jquery.jscrollpane.min.js?179" type="text/javascript"></script>
  		<script src="http://dolphinevpatoria.ru/templates/jblank/js/owl.carousel.js?583" type="text/javascript"></script> 
	</head>
<body>
<div class="carousel_seciton">
		<div class="carusel_wrapper">
			<div class="title_carusel">
				<a href="https://www.instagram.com/dolphinevpatoria/" target="_blank">
					#dolphinevpatoria
				</a>
				<span>
					в Instagram
				</span>
			</div>

				<?php
					if(!empty($inWidget->data->images)){
						if($inWidget->config['imgRandom'] === true) shuffle($inWidget->data->images);
						$inWidget->data->images = array_slice($inWidget->data->images,0,$inWidget->view);
						echo '<div id="widgetData" class="owl-carousel" class="data">';
						foreach ($inWidget->data->images as $key=>$item){
							switch ($inWidget->preview){
								case 'large':
									$thumbnail = $item->large;
									break;
								case 'fullsize':
									$thumbnail = $item->fullsize;
									break;
								default:
									$thumbnail = $item->small;
							}
							echo '<a href="'.$item->link.'" target="_blank" style="display:inline;"><div style="background-image:url('.$thumbnail.'); width:100%; height: 250px;background-position: top;background-size: cover;"></div></a>';
						}
						echo '</div>';
					}
					else echo '<div class="empty">'.$inWidget->lang['imgEmpty'].'</div>';
				?>


		</div>

</div>




</body>
</html>