<?php
namespace BoxOfDevs\Dimensions ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
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
	
    public function onInteract(PlayerInteractEvent $event) {
		if($event->getItem() instanceof FlintSteel) {
			$player = $event->getPlayer();
			if ($event->getBlock()->getId() === 49) {
                    $poses = $this->getPoses($event->getBlock());
					$level = $player->getLevel();
					$start = $poses[0];
					$end = $poses[1];
						$xmin = $start->x;
						$ymin = $start->y;
						$zmin = $start->z;
						$x = $end->x;
						$y = $end->y;
						$z = $end->z;
						for($xmin = $start->x; $xmin <= $x; $xmin++) {
							for($ymin = $start->y; $ymin <= $y; $ymin++) {
								for($zmin = $end->z; $zmin <= $z; $zmin++) {
									if($level->getBlock(new Vector3($xmin, $ymin, $zmin))->getId() === 0) {
										$level->setBlock(new Vector3($xmin, $ymin, $zmin), Block::get(90, 0));
									}
								}
							}
						}
						$event->setCancelled();
			}
		}
	}
	private function getPoses(Block $block){
        $start = new Vector3($block->getX(), $block->getY(), $block->getZ());
        $end = new Vector3($block->getX(), $block->getY(), $block->getZ());

        $i = $block->getId();
        $nottested = [$block];
        $tested = [];
        while(!empty($nottested)){
            /** @var Block $block */
            $block = array_pop($nottested);
            if($block->getId() === $i){
                if($block->getX() > $end->getX()) {$end->x = $block->getX();}
			    if($block->getY() > $end->getY()) {$end->y = $block->getY();}
                if($block->getZ() > $end->getZ()) {$end->z = $block->getZ();}

                if($block->getX() < $start->getX()) {$start->x = $block->getX();}
			    if($block->getY() < $start->getY()) {$start->y = $block->getY();}
                if($block->getZ() < $start->getZ()) {$start->z = $block->getZ();}

                $next = $block->getLevel()->getBlock($block->add(1));
                if(!in_array($next, $tested)) {$nottested[] = $next;}

                $next = $block->getLevel()->getBlock($block->add(-1));
			    if(!in_array($next, $tested)) {$nottested[] = $next;}

                $next = $block->getLevel()->getBlock($block->add(0, 1));
			    if(!in_array($next, $tested)) {$nottested[] = $next;}

                $next = $block->getLevel()->getBlock($block->add(0, -1));
                if(!in_array($next, $tested)) {$nottested[] = $next;}

                $next = $block->getLevel()->getBlock($block->add(0, 0, 1));
			    if(!in_array($next, $tested)) {$nottested[] = $next;}

                $next = $block->getLevel()->getBlock($block->add(0, 0, -1));
                if(!in_array($next, $tested)) {$nottested[] = $next;}
                $tested[] = $block;
            }
        }
        return [$start, $end];
    }
	public function isObsidian(Vector3 $pos, Level $level) {
		if($level->getBlock($pos)->getId() === 49) {
			return true;
		} else  {
			return false;
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
				$newPortal = true;
				for($xmin = $x - 45; $xmin <= $x + 45; $xmin++) {
					for($ymin = $y - 45; $ymin <= $y + 45; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					      if($this->getServer()->getLevelByName("nether")->getBlock(new Vector3($xmin, $ymin, $zmin))->getId() === 90 and $this->getServer()->getLevelByName("nether")->getBlock(new Vector3($xmin + 1, $ymin - 1, $zmin))->getId() ===! 0) {
							  $xtp = $xmin + 1;
							  $ytp = $ymin;
							  $ztp = $zmin;
							  $newPortal = false;
						  }
				        }
				    }
				}
				if($newPortal ===! false) {
					for($xmin = $x - 45; $xmin <= $x + 45; $xmin++) {
					for($ymin = $y - 45; $ymin <= $y + 45; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					      if($this->getServer()->getLevelByName("nether")->getBlock(new Vector3($xmin, $ymin, $zmin))->getId() === 0 and $this->getServer()->getLevelByName("nether")->getBlock(new Vector3($xmin + 1, $ymin - 1, $zmin))->getId() ===! 0) {
							  $xtp = $xmin + 1;
							  $ytp = $ymin;
							  $ztp = $zmin;
						  }
				        }
				    }
				}
				if(!isset($xtp)) {
					$xtp = $x;
					$ytp = $y;
					$ztp = $z;
				}
				$player->teleport(new Vector3($xtp, $ytp, $ztp));
				$this->createPortal($this->getServer()->getLevelByName("nether"), new Vector3($xtp - 2, $ytp, $ztp));
				} else {
				$player->teleport(new Vector3($xtp, $ytp, $ztp));
				}
				$player->sendMessage("Switching to the nether...");
				$this->players[$player->getName()] = "nether";
			} elseif($this->players[$player->getName()] === "nether") {
				$newPortal = true;
				$this->switchLevel($player, $this->getServer()->getDefaultLevel());
				for($xmin = $x - 45; $xmin <= $x + 45; $xmin++) {
					for($ymin = $y - 45; $ymin <= $y + 45; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					      if($this->getServer()->getDefaultLevel()->getBlock(new Vector3($xmin, $ymin, $zmin))->getId() === 90 and $this->getServer()->getDefaultLevel()->getBlock(new Vector3($xmin + 1, $ymin - 1, $zmin))->getId() ===! 0) {
							  $xtp = $xmin + 1;
							  $ytp = $ymin;
							  $ztp = $zmin;
							  $newPortal = false;
						  }
				        }
				    }
				}
				if($newPortal ===! false) {
					for($xmin = $x - 45; $xmin <= $x + 45; $xmin++) {
					for($ymin = $y - 45; $ymin <= $y + 45; $ymin++) {
					   for($zmin = $z - 5; $zmin <= $z + 5; $zmin++) {
					      if($this->getServer()->getDefaultLevel()->getBlock(new Vector3($xmin, $ymin, $zmin))->getId() === 0 and $this->getServer()->getDefaultLevel()->getBlock(new Vector3($xmin + 1, $ymin - 1, $zmin))->getId() ===! 0) {
							  $xtp = $xmin + 1;
							  $ytp = $ymin;
							  $ztp = $zmin;
						  }
				        }
				    }
				}
				if(!isset($xtp)) {
					$xtp = $x;
					$ytp = $y;
					$ztp = $z;
				}
				$player->teleport(new Vector3($xtp, $ytp, $ztp));
				$this->createPortal($this->getServer()->getDefaultLevel(), new Vector3($xtp, $ytp, $ztp - 2));
				} else {
				$player->teleport(new Vector3($xtp, $ytp, $ztp));
				}
				$this->players[$player->getName()] = "overworld";
				$player->sendMessage("Switching to the overworld...");
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
		$level->setBlock(new Vector3($x, $y+3, $z-2), Block::get(49, 0));
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
		if($level->getBlock($pos)->getId() === 90) {
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
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
    switch($command->getName()){
		case "createportal":
		$x = round($sender->x, 0);
		$y = round($sender->y, 0);
		$z = round($sender->z, 0);
		$level = $sender->getLevel();
		if($this->isObsidian(new Vector3($x, $y-1, $z), $level) and $this->isObsidian(new Vector3($x-1, $y-1, $z), $level) and $this->isObsidian(new Vector3($x+1, $y, $z), $level) and $this->isObsidian(new Vector3($x+1, $y+1, $z), $level) and $this->isObsidian(new Vector3($x+1, $y+2, $z), $level) and $this->isObsidian(new Vector3($x+1, $y+3, $z), $level) and $this->isObsidian(new Vector3($x+1, $y+4, $z), $level) and $this->isObsidian(new Vector3($x-2, $y, $z), $level) and $this->isObsidian(new Vector3($x-2, $y+1, $z), $level) and $this->isObsidian(new Vector3($x-2, $y+2, $z), $level) and $this->isObsidian(new Vector3($x-2, $y+3, $z), $level) and $this->isObsidian(new Vector3($x-2, $y+4, $z), $level) )  {
					if ($this->isObsidian(new Vector3($x, $y+4, $z), $level) and $this->isObsidian(new Vector3($x-1, $y+4, $z), $level) and $this->isObsidian(new Vector3($x-2, $y+4, $z), $level) and $this->isObsidian(new Vector3($x+1, $y+4, $z), $level)) {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x-1, $y+2, $z), Block::get(90, 0));
						$sender->sendMessage("Ignited portal!");
				}
		}				else {
					$sender->sendMessage("You're not inside a portal");
				}
				if($this->isObsidian(new Vector3($x, $y-1, $z), $level)
					and $this->isObsidian(new Vector3($x, $y-1, $z-1), $level)
				    and $this->isObsidian(new Vector3($x, $y-1, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y-1, $z+1), $level)
					and $this->isObsidian(new Vector3($x, $y, $z+1), $level)
					and $this->isObsidian(new Vector3($x, $y+1, $z+1), $level)
					and $this->isObsidian(new Vector3($x, $y+2, $z+1), $level)
					and $this->isObsidian(new Vector3($x, $y+3, $z+1), $level)
					and $this->isObsidian(new Vector3($x, $y, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y+1, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y+2, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y+3, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y+4, $z), $level)
					and $this->isObsidian(new Vector3($x, $y+4, $z-1), $level)
				    and $this->isObsidian(new Vector3($x, $y+4, $z-2), $level)
					and $this->isObsidian(new Vector3($x, $y+4, $z+1), $level))  {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y, $z-1), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+1, $z-1), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(90, 0));
						$level->setBlock(new Vector3($x, $y+2, $z-1), Block::get(90, 0));
						$sender->sendMessage("Ingited portal!");
				} else {
					$sender->sendMessage("You're ot inside a portal");
				}
			}
	}
}