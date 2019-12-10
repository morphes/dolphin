<?php
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';

$prices = modDolphin::getPrices($params);
require JModuleHelper::getLayoutPath('mod_dolphin');