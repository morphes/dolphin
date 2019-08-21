<?php
defined('_JEXEC') or die;
?>


<div class="presentation_block">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">


				<ul class="ul_presentation">
				<?php foreach ($list as $item) :  ?>
					<li>
                        <div class="img_present">
							<a href="<?php echo $item->link; ?>"><?php $images = json_decode($item->images); ?><img class="image_intro" src="<?php echo $images->image_intro; ?>" alt="<?php echo $images->image_intro_alt; ?>"/></a>
                        </div>
                        <div class="link_pres">
                            <a href="<?php echo $item->link; ?>">
                                <span>
                                    <?php echo $item->title; ?>
                                </span>
                            </a>
                        </div>
                        <p>
                            <?php echo substr(strip_tags($item->introtext), 0, strpos(strip_tags($item->introtext), ' ', 300)).'...' ; ?>
                        </p>
                        <div class="tel_pres">
                            <a href="tel:<?php echo $images->image_intro_alt; ?>">
                                <?php echo $images->image_intro_alt; ?>
                            </a>
                        </div>
                        <a href='<?php echo $item->link; ?>' class="links_more">
                            Узнать подробности
                        </a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>


                    
