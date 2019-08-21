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


<div class="tabs_switch">
	<ul class="tabs_ul_swith">
		<?php foreach ($list as $group_name => $group) : ?>
		<li>
			<span>
				<mark>
					<?php echo $group_name;?>
				</mark>
			</span>
		</li>
		<?php endforeach; ?>
	</ul>


	<ul class="tabs_ul_content">
		<?php foreach ($list as $group_name => $group) : ?>
		<li>
			<ul class="ul_routine">
				<?php foreach ($group as $item) : ?>
				<li>
					<b>
						<?php echo $item->title; ?>
					</b>
					<?php echo $item->displayIntrotext; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<p>
				* Начало сеансов  уточняйте за сутки
			</p>
			<a href="/zabronirovat.html" class="link_reserve">
				Купить билет
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>





 
