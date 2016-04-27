<?php
namespace BoxOfDevs\Dimensions ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\block\Block;
use pocketmine\Server;
use pocketmine\item\FlintSteel;
use pocketmine\entity\Human;
use pocketmine\network\protocol\SetTimePacket;
use pocketmine\network\protocol\ChangeDimensionPacket;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
 use pocketmine\Player;

class Main extends PluginBase implements Listener{
 const OWERWORLD = "overworld";
 const NETHER = "nether";
 const UNKNOWN = "?";
 // const END;
    public function onJoin(PlayerJoinEvent $event) {
		$this->players[$event->getPlayer()->getName()] = "overworld";
	}
    public function onPlace(BlockPlaceEvent $event) {
		if($event->getItem() instanceof FlintSteel) {
			$x = $event->getBlock()->getX();
			$y = $event->getBlock()->getY();
			$Z = $event->getBlock()->getZ();
				if(new Vector3($x, $y-1, $z) === Block::get(49, 0) and new Vector3($x-1, $y-1, $z) === Block::get(49, 0) and new Vector3($x+1, $y, $z) === Block::get(49, 0) and new Vector3($x+1, $y+1, $z) === Block::get(49, 0) and new Vector3($x+1, $y+2, $z) === Block::get(49, 0) and new Vector3($x+1, $y+3, $z) === Block::get(49, 0) and new Vector3($x+1, $y+4, $z) === Block::get(49, 0) and new Vector3($x-2, $y, $z) === Block::get(49, 0) and new Vector3($x-2, $y+1, $z) === Block::get(49, 0) and new Vector3($x-2, $y+2, $z) === Block::get(49, 0) and new Vector3($x-2, $y+3, $z) === Block::get(49, 0) and new Vector3($x-2, $y+4, $z) === Block::get(49, 0) )  {
					if (new Vector3($x, $y+4, $z) === Block::get(49, 0) and new Vector3($x-1, $y+4, $z) === Block::get(49, 0) and new Vector3($x-2, $y+4, $z) === Block::get(49, 0) and new Vector3($x+1, $y+4, $z) === Block::get(49, 0)) {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y+2, $z), Block::get(90, 0));
				}
				if(new Vector3($x, $y-1, $z) === Block::get(49, 0) 
					and new Vector3($x, $y-1, $z-1) === Block::get(49, 0) 
				    and new Vector3($x, $y-1, $z-2) === Block::get(49, 0) 
					and new Vector3($x, $y-1, $z+1) === Block::get(49, 0) 
					and new Vector3($x, $y, $z+1) === Block::get(49, 0) 
					and new Vector3($x, $y+1, $z+1) === Block::get(49, 0) 
					and new Vector3($x, $y+2, $z+1) === Block::get(49, 0) 
					and new Vector3($x, $y+3, $z+1) === Block::get(49, 0)
					and new Vector3($x, $y, $z-2) === Block::get(49, 0) 
					and new Vector3($x, $y+1, $z-2) === Block::get(49, 0) 
					and new Vector3($x, $y+2, $z-2) === Block::get(49, 0) 
					and new Vector3($x, $y+3, $z-2) === Block::get(49, 0)
					and new Vector3($x, $y+4, $z) === Block::get(49, 0) 
					and new Vector3($x, $y+4, $z-1) === Block::get(49, 0) 
				    and new Vector3($x, $y+4, $z-2) === Block::get(49, 0) 
					and new Vector3($x, $y+4, $z+1) === Block::get(49, 0))  {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y, $z-1), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z-1), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z-1), Block::get(90, 0));
				}
			}
		}
	}
	public function onMove(PlayerMoveEvent $event) {
		$player = $event->getPlayer();
			$x = $player->x;
			$y = $player->y;
			$z = $player->z;
		$block = $player->getLevel()->getBlock(new Vector3($x, $y, $z));
		// if the player pass thouth the portal
		if($block->getId() === 90) {
			$level = $player->getLevel();
			if($this->players[$player->getName()] === "overworld") {
				$this->switchLevel($player, $this->getServer()->getLevelByName("nether"));
				for($xmin = $x - 5; $xmin <= $x + 5; $xmin++) {
					for($ymin = $x - 5; $ymin <= $y + 5; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					       $this->getServer()->getLevelByName("nether")->setBlock(new Vector3($xmin, $ymin, $zmin), Block::get(Block::AIR));
				        }
				    }
				}
				$player->teleport(new Vector3($x, $y, $z));
				$player->sendMessage("Switching to the nether...");
				$this->players[$player->getName()] = "nether";
				$this->createPortal($this->getServer()->getLevelByName("nether"), new Vector3($x - 2, $y, $z));
			} elseif($this->players[$player->getName()] === "nether") {
				$this->switchLevel($player, $this->getServer()->getDefaultLevel());
				for($xmin = $x - 5; $xmin <= $x + 5; $xmin++) {
					for($ymin = $y; $ymin <= $y + 5; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					       $this->getServer()->getDefaultLevel()->setBlock(new Vector3($xmin, $ymin, $zmin), Block::get(Block::AIR));
				        }
				    }
				}
				$player->teleport(new Vector3($x, $y, $z));
				$this->players[$player->getName()] = "overworld";
				$player->sendMessage("Switching to the overworld...");
				$this->createPortal($this->getServer()->getDefaultLevel(), new Vector3($x, $y, $z - 2));
			}
	}
	}
	public function createPortal(Level $level, Vector3 $pos) {
		$x = $pos->x;
		$y = $pos->y;
		$z = $pos->z;
		// down side
		$level->setBlock(new Vector3($x, $y-1, $z), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y-1, $z-1), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y-1, $z-2), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y-1, $z+1), Block::get(49, 0));
		// east and west sides
		$level->setBlock(new Vector3($x, $y, $z+1), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+1, $z+1), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+2, $z+1), Block::get(49, 0));
	    $level->setBlock(new Vector3($x, $y+3, $z+1), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+1, $z-2), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+2, $z-2), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+3, $z-2), Block::get(49, 0));
		// up side
		$level->setBlock(new Vector3($x, $y+3, $z), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+3, $z-1), Block::get(49, 0));
		$level->setBlock(new Vector3($x-2, $y+3, $z-2), Block::get(49, 0));
		$level->setBlock(new Vector3($x, $y+3, $z+1), Block::get(49, 0));
		// portal blocks
		$level->setBlock(new Vector3($x, $y, $z), Block::get(90, 0));
		$level->setBlock(new Vector3($x, $y, $z-1), Block::get(90, 0));
		$level->setBlock(new Vector3($x, $y+1, $z), Block::get(90, 0));
		$level->setBlock(new Vector3($x, $y+1, $z-1), Block::get(90, 0));
		$level->setBlock(new Vector3($x, $y+2, $z), Block::get(90, 0));
		$level->setBlock(new Vector3($x, $y+2, $z-1), Block::get(90, 0));
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
	public function isPortal(Vector3 $pos, Level $level) {
		if($level->getBlock($pos) === Block::get(BLOCK::AIR)) {
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
		if($this->isPortal(new Vector3($x, $y, $z), $level)) {
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
$this->players = [];
}
public function switchLevel(Player $player, Level $targetLevel){
		$oldLevel = $player->getLevel();
		$player->teleport($targetLevel->getSafeSpawn());
	}
}