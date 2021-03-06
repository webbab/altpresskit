<?php 

require 'loadhelper.php';

class PromoterHelper {

	public static function getData($game){
		$data = LoadHelper::loadCached('http://promoterapp.com/dopresskit/' . $game->promoter['product'], PROMOTER_CACHE_DURATION);
		if($data == null) return;

		$promoterxml = simplexml_load_string($data);

		$promoter = XMLHelper::xml2array($promoterxml);
		XMLHelper::collapse($promoter, 'reviews', 'review');

		if (PROMOTER_OVERWRITE || !isset($game->quotes)) $game->quotes = array();

		foreach($promoter['reviews'] as $review){
			$game->quotes[] = array(
				'link' => 		 $review['url'],
				'description' => $review['quote'],
				'name' => 		 $review['reviewerName'],
				'website' => 	 $review['publicationName'],
			);
		}
	}
}
?>