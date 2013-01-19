<?php
class PruebaService
{ 
	public function helloPhp(){
		$logger = Zend_Registry::get('logger');
		//$player = new Player();
		//$stats = $player->getPlayerTeamStatsDetails(44162);
		$logger->info('Hello World');
		return 'Hello world';
	}
	
	public function getPlayerStats(){
		$logger = Zend_Registry::get('logger');
		$player = new Player();
		$stats = $player->getPlayerTeamStatsDetails(44162);
		//$logger->debug($stats);
		$resultado = ArrayToXml::array_to_xml($stats);
		$logger->debug($resultado);
		return $resultado;
	}
}