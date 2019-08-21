<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$canEdit = $this->item->params->get('access-edit');
$info    = $params->get('info_block_position', 0);
?>

<?php echo JLayoutHelper::render('joomla.content.price.intro_image', $this->item); ?>
<div class="wrap_price">
	<?php echo JLayoutHelper::render('joomla.content.price.blog_style_default_item_title', $this->item); ?>
	<?php echo $this->item->introtext; ?>
</div>


