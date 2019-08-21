<?php
defined('_JEXEC') or die;
function modChrome_well($module, &$params, &$attribs)
{
    if (!$module->content) 
    {
        return;
}

    echo "<div class=\"well well-sm" . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";

    if ($module->showtitle) {
        echo "<h3>" . $module->title . "</h3>";
    }

    echo $module->content;
    echo "</div>";
}
function modChrome_span($module, &$params, &$attribs){
if (!empty ($module->content)) {
$module->content = preg_replace('~href=\"(.*?)>(.*?)</a>~si', 'href="$1><span>$2</span></a>', $module->content);
echo $module->content;
}
}
