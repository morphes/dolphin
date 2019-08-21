<?php
defined('_JEXEC') or die;
?>


<div class="title_saitbar">
	<?php echo $module->title; ?>
</div>
<ul class="saitbar_news">
<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<span>
				<?php echo $item->title; ?>
			</span>
		</a>
		<p>
            <?php echo substr(strip_tags($item->introtext), 0, strpos(strip_tags($item->introtext), ' ', 300)).'...' ; ?>
		</p>
		<div class="date">
            <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?>
		</div>
	</li>
<?php endforeach; ?>
</ul>


