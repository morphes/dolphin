<?php
defined('_JEXEC') or die;
?>
<?php
if( !defined('FormmailMakerFormLoader') ){

	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();

};


function phpfmg_form( $sErr = false )
{
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*')->from($db->quoteName('#__tickets'));
    $db->setQuery($query);
    $pricesDb = $db->loadAssocList();

    $prices = [];
    foreach($pricesDb as $price) {
        $prices[$price['id']] = $price;
    }
    $style  = " class='form_text' ";
    if(isset($GLOBALS['_SERVER']) && isset($GLOBALS['_SERVER']['REQUEST_URI'])) {
        if($GLOBALS['_SERVER']['REQUEST_URI'] == '/zabronirovat.html') {
            include 'form_zabronirovat.php';
        }
        if($GLOBALS['_SERVER']['REQUEST_URI'] == '/kupit.html') {
            include 'form_kupit.php';
        }
    }
}
function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
}
?>