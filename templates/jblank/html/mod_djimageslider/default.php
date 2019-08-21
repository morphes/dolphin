<?php
defined('_JEXEC') or die ('Restricted access'); 
$wcag = $params->get('wcag', 1) ? ' tabindex="0"' : ''; ?>
<ul class="slides">
		<?php foreach ($slides as $slide) { 
			$rel = ($slide->rel ? 'rel="'.$slide->rel.'"':''); ?>
			<li>
			<div class="position_slides">
			  <div class="wrap_text_sliders">	
			  	<div class="prevu_slider">
                    <?php echo $slide->description; ?>
                </div>
                <div class="title_slider">
					<?php echo $slide->title; ?>
                </div>
                <a href="<?php echo $slide->link; ?>" class='links_slider'>
                    <?php echo ($params->get('readmore_text',0) ? $params->get('readmore_text') : JText::_('MOD_DJIMAGESLIDER_READMORE')); ?>
                </a>
				
			  </div>
			</div>
			<img class="dj-image" src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>"/>										
		</li>
    <?php } ?>
</ul>
	<div class="partners" id="partners">
    <ul>
        <li>
            <a href="#" class='pr_1'>
            </a>
        </li>
        <li>
            <a href="#"  class='pr_2'>
            </a>
        </li>
        <!-- <li>
            <a href="#"  class='pr_3'>
            </a>
        </li>-->
        <li>
            <a href="#"  class='pr_4'>
            </a>
        </li>
    </ul>
</div>
 
 