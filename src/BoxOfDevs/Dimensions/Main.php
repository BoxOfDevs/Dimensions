<?php
namespace BoxOfDevs\Dimensions ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\block\Block;
use pocketmine\Server;
use pocketminemine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\level\Position
 use pocketmine\Player;

 public OWERWORLD;
 public NETHER;
 public UNKNOWN;
 // private END;

class Main extends PluginBase implements Listener{
	public function onMove(PlayerMoveEvent $event) {
		$player = $event->getPlayer();
		if($this->isInPortal($player)) {
			$x = $player->x;
			$y = $player->y;
			$z = $player->z;
			$level = $player->getLevel();
			if($this->getWorldType($level) === self::OVERWORLD) {
				$netherlevel = $this->getOtherLevel($level);
				$xmin = $x/8-20;
				$ymin = $y-20;
				$zmin = $z/8-20;
				$isteleported = false;
				for($xmin <= $x/8+20, $xmin++) {
					for($ymin <= $y+20, $ymin++) {
						for($zmin <= $z/8+20, $zmin++) {
							if($this->isPortal($xmin, $ymin, $zmin, $netherlevel) and $isteleported ===! true) {
								$pz = $zmin;
								$py = $ymin;
								$px = $xmin;
								$player->switchLevel($netherlevel);
								$player->teleport(new Vector3($px, $py, $pz));
								$isteleported = true;
							}
						}
					}
				} if($isteleported !=== true) {
				$xmin = $x/8-3;
				$ymin = $y;
				$zmin = $z/8-3;
					for($xmin <= $x/8+3, $xmin++) {
						for($ymin <= $y+4, $ymin++) {
							for($zmin <= $z/8+3, $zmin++) {
								$netherlevel->setBlock(new Vector3($min, $ymin, $zmin), Block::get(BLOCK::AIR));
							}
						}
					}
					$player->switchLevel($netherlevel);
					$player->teleport(new Vector3($x, $y, $z));
				}
			}
		}
	}
	public function getOtherLevel(Level $level) {
			 $cfg = new Config($this->getServer()->getWorldFolder() . "dimensions.yml", Config::YAML);
			 $levelinfo = $cfg->get($level->getName());
			 $netherlevel = $this->getServer()->getLevelByName($levelinfo[1]);
			 if($netherlevel instanceof Level) {
				 return $netherlevel;
			 } else {
				 switch($this->getWorldType($level)) {
					 case self::OVERWORLD:
					    return $this->getServer()->getLevelByName($cfg->get("DefaultNether"));
						break;
					 case self::NETHER:
					    return $this->getServer()->getLevelByName($cfg->get("DefaultOverworld"));
						break;
				 }
			 }
	}
	public function isPortal($x, $y, $z, Level $level) {
		if(new Postion($x, $y, $z, $level) === Block::get(BLOCK::PORTAL)) {
			return true;
		} else  {
			return false;
		}
	}
	public function isInPortal(Player $player) {
		$x = $player->x;
		$y = $player->y;
		$z = $player->z;
		$level = $player->getLevel();
		if($this->isPortal($x, $y, $z, $level)) {
			return true;
		} else {
			return false;
		}
	}
	public function getWorldType(Level $level) {
			 $cfg = new Config($this->getServer()->getWorldFolder() . "dimensions.yml", Config::YAML);
			 $leveltype = $cfg->get($level->getName());
			 switch($leveltype[0]) {
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
}