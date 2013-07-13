<?php

class XMLHelper {

	public static function parse($xmlpath){
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($xmlpath, 'SimpleXMLElement', LIBXML_NOCDATA);

		foreach (libxml_get_errors() as $error) {
			echo '<p>XML Error on line ', $error->line, ' column ', $error->column, ': ', $error->message, '</p>';
		}
		
		if(!$xml) die();

		$data = XMLHelper::xml2array($xml);

		XMLHelper::collapse(&$data, 'socials', 'social');
		XMLHelper::collapse(&$data, 'histories', 'history');
		XMLHelper::collapse(&$data, 'trailers', 'trailer');
		XMLHelper::collapse(&$data, 'awards', 'award');
		XMLHelper::collapse(&$data, 'quotes', 'quote');
		XMLHelper::collapse(&$data, 'additionals', 'additional');
		XMLHelper::collapse(&$data, 'credits', 'credit');
		XMLHelper::collapse(&$data, 'contacts', 'contact');

		return $data;
	}

	private static function collapse($data, $plural, $singular){
		$data[$plural] = $data[$plural][$singular];

		// the xml2array function behaves slightly differently if a node has one or multiple children
		// so we need to check if the result of this operation is an associative array (when it's a single item)
		// and if so, we nest it in an array
		if(XMLHelper::isAssoc($data[$plural])) $data[$plural] = array($data[$plural]);
	}

	// stolen from http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-numeric/4254008#4254008
	private static function isAssoc($array) {
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	private static function xml2array ( $xmlObject, $out = array ()) {
		foreach ( (array) $xmlObject as $index => $node ){
			$index = XMLHelper::dashesToCamelCase($index);
			$out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? XMLHelper::xml2array ( $node ) : trim($node);
		}
		return $out;
	}

	// stolen from: http://stackoverflow.com/questions/2791998/convert-dashes-to-camelcase-in-php
	private static function dashesToCamelCase($string, $capitalizeFirstCharacter = false) {
		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

		if (!$capitalizeFirstCharacter) {
			$str[0] = lcfirst($str[0]);
		}

		return $str;
	}
}