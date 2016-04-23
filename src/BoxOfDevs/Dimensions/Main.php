<?php
namespace BoxOfDevs\Dimensions ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\block\Block;
use pocketmine\Server;
 use pocketmine\Player;

 public OWERWORLD;
 public NETHER;
 public UNKNOWN;
 // private END;

class Main extends PluginBase implements Listener{
	public function onMove(PlayerMoveEvent $event) {
		$player = $event->getPlayer();
		$x = $player->x;
		$y = $player->y;
		$z = $player->z;
		$level = $player->getLevel();
		if(new Postion($x, $y, $z, $level) === Block::get(BLOCK::PORTAL)) {
		}
	}
	public function getWorldType(Level $level) {
			 $cfg = new Config($this->getServer()->getWorldFolder() . "dimensions.yml", Config::YAML);
			 $leveltype = $cfg->get($level->getName());
			 switch($leveltype) {
				 case "overworld":
				 return self::OVERWORLD;
				 break;
				 case "nether":
				 return self::NETHER;
				 break;
				 // case "end":
				 // return true;
				 // break;
				 default:
				 return self::UNKNOWN;
				 break;
			 }
	}
public function onEnable(){
$this->getServer()->getPluginManager()->registerEvents($this, $this);
 }
public function onLoad(){
}
 public function onCommand(CommandSender $issuer, Command $cmd, $label, array $params){
switch($cmd->getName()){
}
return false;
 }
}