<?php

namespace Joomla\Module\JkFilter\Site\Helper;
defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Categories\Categories;
use Joomla\Database\ParameterType;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

abstract class JkFilterHelper {
	
	public static function getList(&$params){
		
		$app 			= Factory::getApplication();
		$language		= Factory::getLanguage()->getTag();
		$catid			= self::stripData($app->input->get('id',0,'INT'),true);
		$option 		= $app->input->get('option','','STRING');
		$view			= $app->input->get('view','','STRING');
		$item_ids		= $app->getUserState('jkfilter',array(),'ARRAY');
		$filter 		= $app->getUserStateFromRequest($option.'.cat_'.$catid,'filter',array(),'ARRAY');
		$context		= ($option == 'com_users') ? 'com_users.user' : 'com_content.article';
		
		$fieldsy = FieldsHelper::getFields($context);
		
		//$fields_set		= (!empty($item_ids)) ? self::getFieldsValues($context,$catid,$item_ids,$language) : array();
		//$fields			= self::getFieldsValues($context,$catid,array(),$language);

		for($i=0;$i<count($fields);$i++){
				$fl[$fields[$i]->field_id]['title'] = $fields[$i]->field_name;
				$fl[$fields[$i]->field_id]['template'] = $fields[$i]->field_template;
				//$fl[$fields[$i]->field_id]['field_background'] = $fields[$i]->field_background;
			if(!empty($item_ids)){
				if($fields_set[$i]->value === $fields[$i]->value){
					$option = array('value'=>$fields[$i]->value,'count'=>$fields_set[$i]->count,'checked'=>false);
					$fl[$fields[$i]->field_id]['values'][] = $option;
				}else{
					$option = array('value'=>$fields[$i]->value,'count'=>0,'checked'=>false);
					$fl[$fields[$i]->field_id]['values'][] = $option;
				}
			}else{
				$option = array('value'=>$fields[$i]->value,'count'=>$fields[$i]->count,'checked'=>false);
				$fl[$fields[$i]->field_id]['values'][] = $option;
			}
		}
		
		return $fl;
		
	}
	
	public function getIdCategories($catId=0){
		
		$categories = Categories::getInstance('Content');
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

	public function getFieldsValues($context,$catId,$item_ids = array(),$lang='*'){
		
	//	$db 		= Factory::getDbo();
	//	$query		= $db->getQuery(true);
		//$catIds 	= self::getIdCategories($catId);
		
		
	//$db 	= $this->getDbo();
	//$query 	= $db->getQuery(true);

		//https://github.com/joomla/joomla-cms/blob/de15a4085b54821abdb9599870fb7a6147e08dbf/administrator/components/com_fields/src/Model/FieldModel.php#L611
		
//I want to supplement!
//It is very difficult to work with the **#__fields_values** table, I am currently developing a material filter in which this table slows down the filter. The problem is that the category_id columns are simply needed in the table, This is necessary in order to make a selection already when you are in the parent category, a situation arises when you need to filter the materials of child categories, here a problem arises, you need to make a join from the table **#__fields_categories**, which may NOT contain child categories, but only the parent. Or Make a selection of materials by category, and then join **#__fields_values** to this selection by linking the material id and item_id fields from **#__fields_values** Such selections consume a lot of resources! Since we have to list all the IDs of materials from child categories.
//------
//There may be a conflict with the category IDs of other components, so the context field is also needed the same as in the table **#__fields**
//-----
//The category id column should contain the actual category of the material, not the selected category in the field settings.
//If you do, I will give joomla users a real, fast filter!
		
		
/* 		$query->select($db->quoteName('a.value'));
		$query->select($db->quoteName('a.field_id'));
		$query->select($db->quoteName('a.field_name'));
		$query->select($db->quoteName('a.field_template'));
		$query->select('COUNT(*) AS `count`');
		$query->from($db->quoteName('#__jkfilter','a'));
		$query->whereIn($db->quoteName('a.catid'),$catIds,ParameterType::INTEGER);
		if (!empty($item_ids)) $query->whereIn($db->quoteName('a.item_id'),$item_ids,ParameterType::INTEGER);
		$query->whereIn($db->quoteName('a.language'),[$lang,'*'],ParameterType::STRING);
		$query->where($db->quoteName('a.context').' = '.$db->quote($context));
		$query->where($db->quoteName('a.state').' = 1');
		$query->where($db->quoteName('a.field_template').' != '.$db->quote('off'));
		$query->group($db->quoteName('a.field_id').','.$db->quoteName('a.value'));
		$query->order($db->quoteName('a.value').'=0,-'.$db->quoteName('a.value').' DESC ,'.$db->quoteName('a.value')); */
		
		//$query = "SELECT fv.item_id,fv.field_id,fv.value FROM yrnsv_fields_values AS fv LEFT JOIN yrnsv_fields AS fs ON fs.id = fv.field_id AND fs.type !='subform' AND fs.state = 1 AND fs.context = 'com_content.article'";
		
		
/* 		$qyery = "SELECT fv.item_id,fv.value,fl.id,fl.title,fl.ordering,fl.params,fl.fieldparams, COUNT(fv.item_id) AS count FROM yrnsv_fields AS fl
LEFT JOIN yrnsv_fields_values AS fv ON fv.field_id = fl.id
LEFT JOIN yrnsv_fields_categories AS ct ON ct.field_id = fv.field_id
WHERE fl.context = 'com_content.article' AND ct.category_id IN(24,25,26,27,28,29,30) AND fl.type NOT IN('subform') AND fl.state = 1 
GROUP BY fl.id,fv.value ORDER BY fl.ordering ASC, fv.value=0,-fv.value DESC, fv.value;"; */
		//$time_start = microtime(true);
		//$results = &$db->setQuery($query)->loadColumn();
		
	
		
		//$q		= $db->getQuery(true);
		//$q = 'SELECT field_id, value FROM `yrnsv_fields_values` WHERE `item_id` EXISTS(SELECT id FROM yrnsv_content WHERE catid IN(24,25,26,27,28,29,30));'
		
/* $query2 = 'SELECT fv.field_id, fv.item_id, fv.value, COUNT(fv.item_id) AS count FROM yrnsv_fields_values AS fv
GROUP BY fv.field_id, fv.value ORDER BY fv.value=0,-fv.value DESC, fv.value;';*/
		
		//$results = $db->setQuery($q)->loadAssocList();
		
//$time_end = microtime(true);
//$time = $time_end - $time_start;

//echo "ts $time секунд\n";
		
		//return $results;
		
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

}
