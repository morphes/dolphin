<?php

defined('_JEXEC') or die;

class modPrices
{
    public static function getPrices($params)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__tickets'));
        $db->setQuery($query);
        $result = $db->loadAssocList();
        $tickets = [];
        foreach($result as $ticket) {
            $tickets[$ticket['name']] = $ticket;
        }
        return $tickets;
    }
}