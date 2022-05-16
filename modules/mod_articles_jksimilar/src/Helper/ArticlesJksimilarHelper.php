<?php

namespace Joomla\Module\ArticlesJksimilar\Site\Helper;

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
use Joomla\Component\Content\Site\Model\ArticleModel;

abstract class ArticlesJksimilarHelper {
	
	public static function getList(&$params){
		
		$similarArticles = array();
		$input = &Factory::getApplication()->input;
		$itemId = $input->getInt('id',0);

		if($input->getString('view','') == 'article' && $itemId !== 0){

			$article = new ArticleModel;
			$category = new \stdClass;
			$similarIds = array();
			$similarValues = array();
			$similarArticles = array();
			$similarAccuracy = 0;
			$item = $article->getItem($itemId);
			$category->id = $item->catid;
			$categoryFields = &FieldsHelper::getFields('com_content.categories',$category);
			
			if(!empty($categoryFields)){

				foreach(FieldsHelper::getFields('com_content.article',$item) as &$field){
					$item->jcfields[$field->id] = &$field;
				}
				
				foreach($categoryFields as &$cfield){
					
					if($cfield->name == 'accuracy'){
						$similarAccuracy = (int)$cfield->rawvalue;
					}
					
					if($cfield->name == 'similar-products'){
						
						if(!empty($cfield->rawvalue)){
							foreach($cfield->rawvalue as &$value){
								array_push($similarIds,(int)$value);
								if(!empty($item->jcfields[$value]->rawvalue)){
									if(is_array($item->jcfields[$value]->rawvalue)){
										foreach($item->jcfields[$value]->rawvalue as &$data){
											array_push($similarValues,"'".(string)$data ."'");
										}
									}else{
										array_push($similarValues,"'".(string)$item->jcfields[$value]->rawvalue ."'");
									}
								}
							}
						}
					}
					
				}
				
				if(!empty($similarValues) && !empty($similarIds)){
					
					$db = &Factory::getDbo();
					$query = $db->getQuery(true);
					$query->select($db->quoteName(array('item_id')));
					$query->from($db->quoteName('#__fields_values'));
					$query->where($db->quoteName('field_id').' IN('.implode(',',array_unique($similarIds)).')');
					$query->where($db->quoteName('value').' IN('.implode(',',array_unique($similarValues)).')');
					$query->group($db->quoteName('item_id').' HAVING COUNT(item_id) >='.(count($similarValues)-$similarAccuracy));
					$query->order('RAND()');
					$query->setLimit('12');
					$db->setQuery($query);
					$results = &$db->loadColumn();

					if(!empty($results) && $results !== NULL){
						$similarArticles = array();
						foreach($results as $k => &$id){
							$similarArticles[$k] = &$article->getItem($id);
							foreach(FieldsHelper::getFields('com_content.article',$similarArticles[$k]) as &$field){
								$similarArticles[$k]->jcfields[$field->id] = &$field;
							}
						}
					}
					
				}
				
			}
			
		}
		
		if(count($similarArticles) == 0){
			return false;
		}else{
			return $similarArticles;
		}
		
	}

}
