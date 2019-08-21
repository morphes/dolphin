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


<div class="news_img">
	<?php echo JLayoutHelper::render('joomla.content.news.intro_image', $this->item); ?>
</div>
<div class="text_news">
	<?php echo JLayoutHelper::render('joomla.content.news.blog_style_default_item_title', $this->item); ?>

	<p>
		<?php echo substr(strip_tags($this->item->introtext), 0, strpos(strip_tags($this->item->introtext), ' ', 240)).'...' ; ?>
	</p>


	
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>

<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
	<?php echo JLayoutHelper::render('joomla.content.news.info_block.create_date', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
<?php endif; ?>


</div>
