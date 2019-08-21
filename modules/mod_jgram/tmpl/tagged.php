<div class="jgram tagged <?php echo $classNames; ?>" data-accessToken="<?php echo $accessToken; ?>"
										   data-userID="<?php echo $userID; ?>",
										   data-tag="<?php echo $tagName; ?>"
										   data-limit="<?php echo $limit; ?>"
										   data-restrict="<?php echo $restricted; ?>"
										   data-lightbox="<?php echo $lightbox; ?>"
										   >
	<!-- tagged info -->
	<?php if($hideHashtag != "yes"){ ?>
		<h2>#<?php echo $tagName; ?></h2>
	<?php } ?>

	<!-- feed display-->
	<div class="tagged-feed col-<?php echo $columns; ?> pad-<?php echo $padding; ?> <?php echo $swiperClass; ?>">

	</div>

</div>
