<?php

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Factory;
use Joomla\Database\ParameterType;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Component\Fields\Administrator\Model\FieldsModel;

class plgSystemJkFilter extends CMSPlugin {
	
	protected $autoloadLanguage = true;
	
	public function onContentAfterSave($context, $item, $isNew, $data=[]){

		
		if($context=='com_content.article' 
		|| $context=='com_contacts.contact'
		|| $context=='com_users.user'){
			
			if(isset($data['com_fields']) && !empty($data['com_fields'])){
				self::query($context, $item->id);
			}
			
		}
		
		if($context=='com_fields.field'){
			self::query($context, $item->id);
		}
		
	}
	
	public function onContentAfterBatchSave($context, $item){
		
		if($context=='com_content.article' 
		|| $context=='com_fields.field'
		|| $context=='com_contacts.contact'
		|| $context=='com_users.user'){
			
			if(is_array($item)){
				$count = count($item);
				for($i=0;$i<$count;$i++){
					self::query($context, $item[$i]);
				}
			}
			
		}
		
	}
	
	public function onContentAfterDelete($context, $item, $state){
		
		if($context=='com_content.article' 
		|| $context=='com_fields.field'
		|| $context=='com_contacts.contact'
		|| $context=='com_users.user'){
		
			if(is_array($item)){
				$count = count($item);
				for($i=0;$i<$count;$i++){
					self::query($context, $item[$i], $state);
				}
			}
		}
		
	}
	
	public function onUserAfterSave($userData){

	}
	
	public function onContentChangeState($context, $item, $state){
		
		if($context=='com_content.article' 
		|| $context=='com_fields.field'
		|| $context=='com_contacts.contact'
		|| $context=='com_users.user'){
			
			if(is_array($item)){
				$count = count($item);
				for($i=0;$i<$count;$i++){
					self::query($context, $item[$i], $state);
				}
			}
			
		}
		
	}
	
	public function onContentPrepareForm($form,$data){
		
		$app = Factory::getApplication();
		
		if($app->isClient('administrator')){
			
			switch($form->getName()){
				case 'com_fields.field.com_content.article':
				case 'com_fields.field.com_contact.contact':
				case 'com_fields.field.com_users.user':
					JForm::addFormPath(__DIR__ .'/src');
					$form->loadFile('params',false);
				break;
			}
			
			return true;
			
		}
		
	}
	
	public function onAfterRoute(){
		
		$app 	= &Factory::getApplication();
		$option	= $app->input->get('option','','STRING');
		$view	= $app->input->get('view','','STRING');
		
		if($app->isClient('administrator')){
			
			$view = $app->input->get('view','','STRING');
			
			switch($option){
				case 'com_content':
				switch($view){
					case 'articles': case 'article':
					require_once __DIR__ .'/src/Model/ArticleModel.php';
					break;
				}
				break;
				case 'com_contact':
				switch($view){
					case 'contacts': case 'contact':
					//require_once __DIR__ .'/src/Model/...........php';
					break;
				}
				break;
				case 'com_users':
				switch($view){
					case 'users': case 'user':
					//require_once __DIR__ .'/src/Model/...........php'; 
					break;
				}
				break;
				case 'com_fields':
				switch($view){
					case 'fields': case 'field':
					//require_once __DIR__ .'/src/Model/ArticleModel.php';
					break;
				}
				break;
			}
			
		}

		if($app->isClient('site') && ModuleHelper::isEnabled('mod_jkfilter')){
			
			$db 		= &Factory::getDbo();
			$user 		= &Factory::getUser();
			$lang		= &Factory::getLanguage()->getTag();
			$catId		= self::stripData($app->input->get('id',0,'INT'),true);
			$groups 	= $user->getAuthorisedViewLevels();
			$sets		= $field_ids = array();
			$ordering	= '';

			if($option == 'com_content' && $view =='category'){
				
				$query		= $db->getQuery(true);
				$filter 	= $app->getUserStateFromRequest($option.'.cat_'.$catId,'filter',array(),'ARRAY');
				$field_ids	= array_filter(array_unique(array_keys($filter)),'is_int');
				$catIds 	= self::getIdCategories($catId);
				
				
				//$query->select($db->quoteName('a.value'));
				//$query->select($db->quoteName('a.item_id'));
				//$query->select($db->quoteName('a.field_id'));
				//$query->select($db->quoteName('a.field_name'));
				//$query->select($db->quoteName('a.field_template'));
				//$query->from($db->quoteName('#__jkfilter','a'));
				//$query->whereIn($db->quoteName('a.catid'),$catIds,ParameterType::INTEGER);
				//if(!empty($field_ids)){
				//	$query->whereIn($db->quoteName('a.field_id'),$field_ids,ParameterType::INTEGER);
				//}
				//$query->whereIn($db->quoteName('a.language'),[$lang,'*'],ParameterType::STRING);
				//$query->whereIn($db->quoteName('a.type'),['text','list','radio','calendar'],ParameterType::STRING);
				//$query->where($db->quoteName('a.context').' = '.$db->quote('com_content.article'));
				//$query->where($db->quoteName('a.state').' = 1');
				//$results = &$db->setQuery($query)->loadObjectList();
				//$app->setUserState('jkfilter',$results);
				
				
				//for($i=0;$i<count($results);$i++){
					
			//	}
				
				//foreach($results as &$data){
					//$arr[$data->field_id][$data->value] = $data->value;
				//}
				
				//print '<pre>';
				//var_dump(array_unique(array_keys($results)));
				//var_dump(array_values($results));
				//array_search('green',$results)
				
				
				//print '<pre>';
				//var_dump($arr);
				
/* 				foreach($results as $data){
					
					var_dump(array_filter($data, function($v, $k) {
						return $k == 'field_template' && $v == 'off';
					}, ARRAY_FILTER_USE_BOTH));
				} */
				
				
/* 				$search_array = array('field_template' => 'off', "second" => 4);
				if(array_key_exists('first',$results)) {
					echo "The 'first' element is in the array";
				}
				
				
				field_template
				
				array_search('green',$array)
				
				print '<pre>';
				var_dump($results); */

/* 				if(count($filter)){

					if(isset($filter['ordering']) && !empty($filter['ordering'])){
						if(is_string($filter['ordering'])){
							$ordering = self::stripData($filter['ordering'],false,100);
							unset($filter['ordering']);
						}
					}
					
					if(isset($filter['sets']) && !empty($filter['sets'])){
						if(is_array($filter['sets'])){
							$sets = $filter['sets'];
							unset($filter['sets']);
						}
					}
					
					if($results !== NULL){
						
						foreach($filter as $k => $params){
							if(is_int($k) && self::stripData($k,true) >= 0 && is_array($params)){
								if(array_key_exists('from',$params) && array_key_exists('to',$params)){
									if(($from = self::stripData($params['from'],true)) <= ($to = self::stripData($params['to'],true))){	

										
									}
								}else{
									foreach($params as $n => $param){
										if(!empty($value = self::stripData($param,false,100))){
											$static['values'][] = $value;
										}
									}
								}
							}
						}
						
					}
					
				}
				
				if(!empty($ordering) || $results !== NULL){
					
					$app->setUserState('filter.article_id',$results);
					$app->setUserState('list.ordering',$ordering);
					
					if(version_compare(JVERSION,'4.0.0','>=')){
						require_once __DIR__ .'/src/Model/CategoryModel.php';
					}
					
				} */
				
				
				
				

		
	
/* 		TRUNCATE `#__jkfilter`"
	
		SELECT fl.ordering,fv.item_id,fl.type,fv.value,fl.id,fl.title,fl.params,fl.fieldparams, COUNT(fv.item_id) AS count 
		FROM yrnsv_fields AS fl
		LEFT JOIN yrnsv_fields_values AS fv ON fv.field_id = fl.id
		LEFT JOIN yrnsv_content AS cn ON cn.id = fv.item_id
		WHERE cn.catid IN(24,25,26,27,28,29,30) AND fl.type NOT IN('subform') AND fl.state = 1 AND fl.context = 'com_content.article'
		GROUP BY fl.id,fv.value  
		ORDER BY fl.ordering ASC, fv.value=0,-fv.value DESC, fv.value;
		
		

		
SELECT fv.item_id,fl.ordering,cn.catid,fl.type,fv.value,fl.id,fl.title,fl.params,fl.fieldparams AS count 
FROM yrnsv_fields AS fl
LEFT JOIN yrnsv_fields_values AS fv ON fv.field_id = fl.id
LEFT JOIN yrnsv_content AS cn ON cn.id = fv.item_id
WHERE cn.catid IN(24,25,26,27,28,29,30) AND fl.type NOT IN('subform') AND fl.state = 1 AND fl.context = 'com_content.article'  
ORDER BY fl.ordering ASC, fv.value=0,-fv.value DESC, fv.value;
		
		
SELECT fl.ordering,cn.catid,fl.type,fv.value,fl.id,fl.title,fl.params,fl.fieldparams, COUNT(fv.item_id) AS count 
FROM yrnsv_fields AS fl
LEFT JOIN yrnsv_fields_values AS fv ON fv.field_id = fl.id
LEFT JOIN yrnsv_content AS cn ON cn.id = fv.item_id
WHERE cn.catid IN(24,25,26,27,28,29,30) AND fl.type NOT IN('subform') AND fl.state = 1 AND fl.context = 'com_content.article'
GROUP BY cn.catid,fl.id,fv.value  
ORDER BY `fl`.`id` ASC
		
		

		
		
		
		
		
		
		
		SELECT MAX(b) FROM
(SELECT MAX(modified_time) AS b FROM `yrnsv_fields` WHERE context = 'com_content.article'
UNION
SELECT MAX(modified) AS b FROM `yrnsv_content`) as b; */
				
				
				
				
				
				
			}
			
		}else{
			
			return false;
			
		}
		
	}
	
	public function stripData($data,$intType=false,$length=0){
		
		$quotes 	= array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
		$goodquotes = array('-', '+', '#','"');
		$repquotes	= array("\-", "\+", "\#","&quot;");
		
		if($length && !$intType){
			$data = mb_substr($data,0,$length);
		}
		
		if($intType){
			$data = mb_substr($data,0,11);
		}

		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim(strip_tags($data));
		$data = str_replace($quotes,'',$data);
		$data = str_replace($repquotes,$goodquotes,$data);
  
		if($intType){
			$data = (int)preg_replace('/[^0-9]/','',$data);
			if($data < 0) $data = 0;
			if($data > 2147483647) $data = 2147483647;
			
		}
		
		return $data;
		
	}
	
	public function getIdCategories($catId=0){
		
		$categories = JCategories::getInstance('Content');
		$children   = $categories->get($catId)->getChildren();
		
		if(!empty($children)){
			$map_item = function ($category){
				return (int)$category->id;
			};
			$ids = array_map($map_item,$children);
			array_push($ids,$catId);
			return array_values($ids);
		}else{
			return array($catId);
		}
		
	}
	
	public function query($context=null,$id=0,$state=1){

		if(!is_null($context)){

			$db 	= &Factory::getDbo();
			$query	= $db->getQuery(true);

			switch($context){
				case 'com_fields.field' 	: $fieldid	= self::stripData($id,true);break;
				case 'com_content.article'	: $itemid	= self::stripData($id,true);break;
				default: $itemid = 0; $fieldid = 0;
			}
			
			$query->delete('#__jkfilter');
			if($itemid > 0) $query->where($db->quoteName('item_id').' = :itemid');
			if($fieldid > 0) $query->where($db->quoteName('field_id').' = :fieldid');
			$query->where($db->quoteName('context').' = :context');
			$query->bind(':context', $context, ParameterType::STRING);
			if($itemid > 0) $query->bind(':itemid', $itemid, ParameterType::INTEGER);
			if($fieldid > 0) $query->bind(':fieldid', $fieldid, ParameterType::INTEGER);
			$db->setQuery($query);
			$db->execute();
			
			if($state == 1){
				
				$query	= $db->getQuery(true);
				$query->select($db->quoteName('fl.ordering'));
				$query->select($db->quoteName('fv.item_id'));
				$query->select($db->quoteName('fv.field_id'));
				$query->select($db->quoteName('cn.catid','category_id'));
				$query->select($db->quoteName('fl.title'));
				$query->select('IFNULL(JSON_UNQUOTE(JSON_EXTRACT('.$db->quoteName('fl.fieldparams').',JSON_UNQUOTE(REPLACE(JSON_SEARCH('.$db->quoteName('fl.fieldparams').','.$db->quote('one').','.$db->quoteName('fv.value').'),'.$db->quote('.value').','.$db->quote('.name').')))),'.$db->quoteName('fv.value').') AS '.$db->quoteName('name'));
				$query->select($db->quoteName('fv.value'));
				$query->select($db->quoteName('fl.type'));
				$query->select('IFNULL(JSON_UNQUOTE(JSON_EXTRACT('.$db->quoteName('fl.params').','.$db->quote('$.field_template').')),'.$db->quote('off').') AS '.$db->quoteName('field_template'));
				$query->select('IFNULL(JSON_UNQUOTE(JSON_EXTRACT('.$db->quoteName('fl.params').','.$db->quote('$.field_background').')),'.$db->quote('off').') AS '.$db->quoteName('field_background'));
				$query->select($db->quoteName('fl.context'));
				$query->from($db->quoteName('#__fields','fl'));
				$query->join('LEFT',$db->quoteName('#__fields_values','fv').'ON'.$db->quoteName('fv.field_id').'='.$db->quoteName('fl.id'));
				$query->join('LEFT',$db->quoteName('#__content','cn').'ON'.$db->quoteName('cn.id').'='.$db->quoteName('fv.item_id'));
				$query->whereNotIn($db->quoteName('fl.type'),['subform','media'],ParameterType::STRING);
				$query->where($db->quoteName('fl.state').' = 1');
				$query->where($db->quoteName('fl.context').' = '.$db->quote($context));
				$query->where($db->quoteName('fv.item_id').' IS NOT NULL');
				$query->where($db->quoteName('fv.field_id').' IS NOT NULL');
				if($itemid > 0) $query->where($db->quoteName('fv.item_id').' = '. $itemid);
				if($fieldid > 0) $query->where($db->quoteName('fl.id').' = '. $fieldid);
				$results = &$db->setQuery($query)->loadObjectList();

				if($count = count($results)){
					for($i=0;$i<$count;$i++){
						$db->insertObject('#__jkfilter',$results[$i]);
					}
				}
				
			}

		}
		
	}
	
    public function onAfterInitialise(){


    }
	
}

?>