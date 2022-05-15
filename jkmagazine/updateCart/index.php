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

if(isset($_POST['update']) && !empty($_POST['update']) && $_POST['update'] !== NULL && is_string($_POST['update'])){
	
	$data = json_decode($_POST['update']);
	
	if(json_last_error() === 0){
		
		$db = &JFactory::getDbo();
		$data->count = strip_data($data->count);
		$data->id =	strip_data($data->id);
		
		if($data->count !== 'remove'){
			
			$query = $db->getQuery(true);
			$query->select(array('b.params'));
			$query->from($db->quoteName('#__content','a'));
			$query->join('LEFT', $db->quoteName('#__categories','b') .' ON '. $db->quoteName('a.catid') .' = '. $db->quoteName('b.id'));
			$query->where($db->quoteName('a.state') . ' = 1 AND '. $db->quoteName('a.id').' = '. $db->quote($data->id));
			$db->setQuery($query);
			$results = $db->loadObject();

			if($results !== NULL){
				
				if(isset(json_decode($results->params)->category_layout)){
					$layout = json_decode($results->params)->category_layout;
					if(in_array('produkts',explode(':',$layout))){
						$sess = $_SESSION['cart'];				
						$sess[$data->id]['id'] = $data->id;
						$sess[$data->id]['count'] = $data->count;
						$_SESSION['cart'] = $sess;
					}
				}
				
			}
			
		}else{
			$sess = $_SESSION['cart'];
			unset($sess[$data->id]);
			if(!empty($sess)){
				$_SESSION['cart'] = $sess;
			}else{
				unset($_SESSION['cart']);
			}
		}
		
	}

	if($user->id !== 0){
		$field = new stdClass();
		$field->field_id = 109;
		$field->item_id = $user->id;
		$field->value = json_encode($_SESSION['cart']);
		$db->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
	}
	
}

?>