<?php

namespace Joomla\Module\ArticlesJkreview\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;

abstract class ArticlesJkreviewHelper {
	
	public static function getList(&$params){
		
		$input = &Factory::getApplication()->input;
		$itemId = $input->getInt('id',0);
		
		if($input->getString('view','') == 'article' && $itemId !== 0){
			$params->get('catid', array());
			ArticlesJkreviewHelper::csrfToken();
			$reviews = ArticlesJkreviewHelper::getAllByItemId($itemId);
			return $reviews;
		}else{
			return false;
		}

	}
	
	public function getAllByItemId($id=false){
		
		if($id){
			$db = &Factory::getDbo();
			$data = new \stdClass();
			
			$query = $db->getQuery(true)
				->select('*')
				->from('#__jkreview_reviews AS a')
				->join('LEFT', '#__jkreview_assets AS b ON b.review_id = a.id')
				->join('LEFT', '#__jkreview_attachments AS c ON c.review_id = a.id')
				->where('a.public_status=1 AND b.item_id='.(int)$id);
			$db->setQuery($query);
			$data = $db->loadObjectList();
			$newData = array();
			
			if(!empty($data)){
				
				foreach($data as $k => $review){
					
					$newData[$review->id]->id = $review->id;
					$newData[$review->id]->text = $review->text;
					$newData[$review->id]->rating = $review->rating;
					$newData[$review->id]->user_id = $review->system_user_id;
					$newData[$review->id]->user_name = $review->user_name;
					$newData[$review->id]->public_date = $review->public_date;
					
					if(!empty($review->data) && !empty($review->mindata)){
						$attach = new \stdClass;
						$attach->data = $review->data;
						$attach->mindata = $review->mindata;
						$newData[$review->id]->attachments[$review->attach_id] = $attach;
					}else{
						$newData[$review->id]->attachments = array();
					}
					
				}
				
				$data = $newData;
				
			}else{
				$data = false;
			}
			
		}else{
			$data = false;
		}

		return $data;
		
	}
	
	public function getTotalByItemId($id=false){
		
		if($id){
			$db = &Factory::getDbo();
			$data = new \stdClass();
			$query = $db->getQuery(true)
				->select('SUM(rating)/COUNT(rating) AS sum, COUNT(rating) AS count')
				->from('#__jkreview_reviews AS a, #__jkreview_assets AS b')
				->where('a.public_status=1 AND a.id = b.review_id AND b.item_id='.(int)$id);
			$db->setQuery($query);
			$data = $db->loadObjectList()[0];
		}else{
			$data = false;
		}

		return $data;
		
	}
	
	public function csrfToken(){
		
		$config = Factory::getConfig();
		$sk = $config->get('secret');
		$rand = md5(md5(time()).''.rand(1,1000));
		$_SESSION['tokencsrf'] = md5(md5($rand.''.$sk));
		setcookie('tokencsrf',$rand);
		
	}

}
