<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.caption');
?>
<ul class="price_ul">

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
			<?php foreach ($this->lead_items as &$item) : ?>
				<li>
					<?php
					$this->item = & $item;
					echo $this->loadTemplate('item');
					?>
				</li>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
	<?php endif; ?>



</ul>
<div class="line_weves"></div>
