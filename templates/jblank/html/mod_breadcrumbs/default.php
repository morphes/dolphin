<?php
defined('_JEXEC') or die;
?>

<div class="cover">
	<?php if ($params->get('showHere', 1)) : ?>
		<li>
			<?php echo JText::_('MOD_BREADCRUMBS_HERE'); ?>&#160;
		</li>
	<?php else : ?>
		<li class="active">
			<span class="divider icon-location"></span>
		</li>
	<?php endif; ?>

	<?php
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
	foreach ($list as $key => $item) :
		if ($key != $last_item_key) :
			// Render all but last item - along with separator ?>
			<span>
				<?php if (!empty($item->link)) : ?>
					<a itemprop="item" href="<?php echo $item->link; ?>" class="pathway"><span itemprop="name"><?php echo $item->name; ?></span></a>
				<?php else : ?>
					<span itemprop="name">
						<?php echo $item->name; ?>
					</span>
				<?php endif; ?>

				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</span>
		<?php elseif ($show_last) :
			// Render last item if reqd. ?>
			<span>
				<span itemprop="name">
					<?php echo $item->name; ?>
				</span>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</span>
		<?php endif;
	endforeach; ?>
</div>
