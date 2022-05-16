<?php

namespace Joomla\Module\ArticlesJkrelated\Site\Helper;

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

abstract class ArticlesJkrelatedHelper {
	
	public static function getList(&$params){
		
		$relatedArticles = array();
		$input = &Factory::getApplication()->input;
		$itemId = $input->getInt('id',0);

		if($input->getString('view','') == 'article' && $itemId !== 0){

			$article = new ArticleModel;
			$category = new \stdClass;
			$relatedIds = array();
			$relatedValues = array();
			$relatedArticles = array();
			$relatedAccuracy = 0;
			$item = $article->getItem($itemId);
			$category->id = $item->catid;
			$categoryFields = &FieldsHelper::getFields('com_content.categories',$category);
			
			if(!empty($categoryFields)){

				foreach(FieldsHelper::getFields('com_content.article',$item) as &$field){
					$item->jcfields[$field->id] = &$field;
				}
				
				foreach($categoryFields as &$cfield){
					
					if($cfield->name == 'accuracy'){
						$relatedAccuracy = (int)$cfield->rawvalue;
					}
					
					if($cfield->name == 'similar-products'){
						
						if(!empty($cfield->rawvalue)){
							foreach($cfield->rawvalue as &$value){
								array_push($relatedIds,(int)$value);
								if(!empty($item->jcfields[$value]->rawvalue)){
									if(is_array($item->jcfields[$value]->rawvalue)){
										foreach($item->jcfields[$value]->rawvalue as &$data){
											array_push($relatedValues,"'".(string)$data ."'");
										}
									}else{
										array_push($relatedValues,"'".(string)$item->jcfields[$value]->rawvalue ."'");
									}
								}
							}
						}
					}
					
				}
				
				if(!empty($relatedValues) && !empty($relatedIds)){
					
					$db = &Factory::getDbo();
					$query = $db->getQuery(true);
					$query->select($db->quoteName(array('item_id')));
					$query->from($db->quoteName('#__fields_values'));
					$query->where($db->quoteName('field_id').' IN('.implode(',',array_unique($relatedIds)).')');
					$query->where($db->quoteName('value').' IN('.implode(',',array_unique($relatedValues)).')');
					$query->group($db->quoteName('item_id').' HAVING COUNT(item_id) >='.(count($relatedValues)-$relatedAccuracy));
					$query->order('RAND()');
					$query->setLimit('6');
					$db->setQuery($query);
					$results = &$db->loadColumn();

					if(!empty($results) && $results !== NULL){
						$relatedArticles = array();
						foreach($results as $k => &$id){
							$relatedArticles[$k] = &$article->getItem($id);
							foreach(FieldsHelper::getFields('com_content.article',$relatedArticles[$k]) as &$field){
								$relatedArticles[$k]->jcfields[$field->id] = &$field;
							}
						}
					}
					
				}
				
			}
			
		}
		
		if(count($relatedArticles) == 0){
			return false;
		}else{
			return $relatedArticles;
		}
		
	}

}
