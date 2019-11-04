<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>


<div class="routine_block">
    <div class="time_session">
        Начало сеансов:
    </div>
    <div class="inline_block">
		<?php foreach ($list as $group_name => $group) : ?>
			<?php foreach ($group as $item) : ?>
			<p>	<?php echo $item->title; ?>
				<?php echo substr(strip_tags($item->title), 0, strpos(strip_tags($item->title), ' ', 1)) ; ?>

				<b>
				<?php echo $item->displayIntrotext; ?>
				</b>
			</p>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>

    <a href="/kupit.html">
        Купить билет
    </a>
    <a href="/zabronirovat.html">
        Забронировать
    </a>
    <div class="clear"></div>
</div>







