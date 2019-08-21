<?php
defined('_JEXEC') or die;
?>



<div class="foto_contain">

	<?php foreach ($list as $group_name => $group) : ?>
	<div class="item_foto">
 
		<ul>
			<?php foreach ($group as $item) : ?>
			<li>
				<?php $images = json_decode($item->images); ?><a data-fancybox-group="gallery" href="<?php echo $images->image_intro; ?>"><img class="image_intro" src="<?php echo $images->image_intro; ?>" alt="<?php echo $images->image_intro_alt; ?>"/></a>
			</li>
			<?php endforeach; ?>
		</ul>
			<span>
				<?php echo $group_name;?>
			</span>
	
	</div>
	<?php endforeach; ?>

</div>






 
