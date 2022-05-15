<?php

function getUserId($username){
	
    $db = \Joomla\CMS\Factory::getDbo();
    $query = $db->getQuery(true)
        ->select($db->quoteName('id'))
        ->from($db->quoteName('#__users'))
        ->where($db->quoteName('username') . ' = ' . $db->quote($username));
    $db->setQuery($query, 0, 1);
    return $db->loadResult();
	
}

?>