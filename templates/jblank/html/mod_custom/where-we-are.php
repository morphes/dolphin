<?php
defined('_JEXEC') or die;
?>
<?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('name','suburb','telephone','mobile')));
$query->from($db->quoteName('#__contact_details'));
$query->where($db->quoteName('id') . ' = 1'); 
$db->setQuery($query);
$result = $db->loadObject();
$db2 = JFactory::getDbo();
$query2 = $db2->getQuery(true);
$query2->select($db2->quoteName(array('name','suburb','telephone','mobile')));
$query2->from($db2->quoteName('#__contact_details'));
$query2->where($db2->quoteName('id') . ' = 2'); 
$db2->setQuery($query2);
$result2 = $db2->loadObject();
$db3 = JFactory::getDbo();
$query3 = $db3->getQuery(true);
$query3->select($db3->quoteName(array('name','suburb','telephone','mobile')));
$query3->from($db3->quoteName('#__contact_details'));
$query3->where($db3->quoteName('id') . ' = 3'); 
$db3->setQuery($query3);
$result3 = $db3->loadObject();
?>
<h3>
		<?php echo $module->title; ?>
</h3>
<ul class="location_address">
    <li>
        <h4>
            <?php echo $result->name; ?>
        </h4>
        <div class="link_address">
            <a href="/kontakty.html">
                <span>
                    <?php echo $result->suburb; ?>
                </span>
            </a>
        </div>
        <p>
            Телефон:
            <a href="tel:<?php echo $result->mobile; ?>">
                <?php echo $result->mobile; ?> ,
            </a>
            <a href="tel:<?php echo $result->telephone; ?>">
                <?php echo $result->telephone; ?>
            </a>
        </p>
    </li>
    <li>
        <h4>
            <?php echo $result2->name; ?>
        </h4>
        <div class="link_address">
            <a href="/kontakty.html">
                <span>
                    <?php echo $result2->suburb; ?>
                </span>
            </a>
        </div>
        <p>
            Телефон:
            <a href="tel:<?php echo $result3->mobile; ?>">
                <?php echo $result2->mobile; ?>
            </a>
        </p>
    </li>
    
</ul>
