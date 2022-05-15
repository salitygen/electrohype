<?php
define('_JEXEC', 1);
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
$container = \Joomla\CMS\Factory::getContainer();
$container->alias('session.web', 'session.web.site')
	->alias('session', 'session.web.site')
	->alias('JSession', 'session.web.site')
	->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');
$app = $container->get(\Joomla\CMS\Application\SiteApplication::class);
$user = &JFactory::getUser();

if(isset($_POST['json']) && !empty($_POST['json']) && $_POST['json'] !== NULL && is_string($_POST['json'])){
	
	$data = json_decode($_POST['json']);
	
	if(json_last_error() === 0){
		
		$db = &JFactory::getDbo();
		$data->id =	strip_data($data->id);
		
		$query = $db->getQuery(true);
		$query->select(array('b.params'));
		$query->from($db->quoteName('#__content','a'));
		$query->join('LEFT', $db->quoteName('#__categories','b') .' ON '. $db->quoteName('a.catid') .' = '. $db->quoteName('b.id'));
		$query->where($db->quoteName('a.state') . ' = 1 AND '. $db->quoteName('a.id').' = '. $db->quote($data->id));
		$db->setQuery($query);
		$results = &$db->loadObject();
		
 		if($results !== NULL){
			
			if(!empty($favorites = $_SESSION['favorites'])){
				if(is_array($favorites)){
					if(!in_array($data->id,$favorites)){
						array_push($favorites,$data->id);
					}
				}
			}else{
				$favorites = array($data->id);
			}

			$favorites = array_values(array_unique($favorites));
		
			if(count($favorites) > 100){
				$_SESSION['favorites'] = array_slice($favorites,0,100);
			}else{
				$_SESSION['favorites'] = $favorites;
			}

			if($user->id !== 0){
				$field = new stdClass();
				$field->field_id = 110;
				$field->item_id = $user->id;
				$field->value = json_encode($_SESSION['favorites']);
				$db->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
			}
			
		}
		
	}

}

echo json_encode(array('ok'));


function strip_data($text){
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
	$goodquotes = array('-', '+', '#','"');
	$repquotes = array("\-", "\+", "\#","&quot;");
	$text = mb_substr($text,0,5);
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	$int = (int)preg_replace('/[^0-9]/','',$text);
	if ($int <= 0) $int = 1;
	if ($int > 2147483647) $int = 2147483647;
	return $int;
}

?>