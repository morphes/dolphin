<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
<div class="date">
<?php echo JText::sprintf(JHtml::_('date', $displayData['item']->created, JText::_('DATE_FORMAT_LC3'))); ?>
</div>