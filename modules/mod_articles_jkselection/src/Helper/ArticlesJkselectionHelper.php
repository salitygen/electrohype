<?php

namespace Joomla\Module\ArticlesJkselection\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

abstract class ArticlesJkselectionHelper {
	
	public static function getList(&$params){
		
		$selectionArticles = array();
		$app = &Factory::getApplication();
		$input = $app->input;
		$catId = $input->get('id',0,'INT');
		$option = $input->get('option','','STRING');
		$jkFieldsFilter = array();
		$ordering = array();
		
		if($option == 'com_content'){
			$context = $option.'.cat_'.$catId.'.jlcontentfieldsfilter';
			$jkFieldsFilter = $app->getUserStateFromRequest($context,'filter',array(),'ARRAY');
			$ordering = $jkFieldsFilter['ordering'];
		}

		if($input->get('view','','STRING') == 'category' && $catId !== 0){
			
			$category = new \stdClass;
			$category->id = $catId;
			$categoryFields = &FieldsHelper::getFields('com_content.categories',$category);
			$options = array();
			$fieldIdName = '118';
			$fieldIdParams = '117';
			
			foreach($categoryFields as &$field){
				
				if($field->name == 'selection'){
					
					$data = json_decode($field->rawvalue,true);
					$data = array_values($data);
					
					for($i=0;$i<count($data);$i++){
						
						foreach($data[$i]['field'.$fieldIdParams] as $param){
							
							$arr = json_decode($param,true);
							$counter = 0;
							
							foreach($arr as $k => $value){
								
								$options[$i]['title'] = $data[$i]['field'.$fieldIdName];
								
								if(isset($jkFieldsFilter[$k])){
									foreach($jkFieldsFilter[$k] as $filter){
										if($filter === $value) $counter++;
									}
								}
								
								if($counter == count($arr)){
									$options[$i]['selected'] = true;
								}else{
									$options[$i]['selected'] = false;
								}

								$options[$i]['params'][] = array('key'=>$k,'value'=>$value);
								
							}
						}
						
					}
					
				}
				
			}
			
		}
		
		if(count($options) == 0){
			return false;
		}else{
			return $options;
		}
		
	}

}
