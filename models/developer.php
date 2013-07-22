<?php

require 'model.php';
require 'helpers/xmlhelper.php';

class Developer extends Model {
	
	public $games = array();

	public function __construct() {
		parent::__construct('', XMLHelper::parse('data/data.xml'));

		if ($this->data == null) ErrorHelper::logError('Failed to load developer data');

		$gamedirs = FileHelper::getGames('data');
		foreach($gamedirs as $gamedir){
			$this->games[$gamedir] = new Game($gamedir);
		}
	}

}
?>