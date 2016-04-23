<?php
namespace BoxOfDevs\Dimensions ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\block\Block;
use pocketmine\Server;
use pocketmine\item\FlintSteel;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
 use pocketmine\Player;

class Main extends PluginBase implements Listener{
 const OWERWORLD = "overworld";
 const NETHER = "nether";
 const UNKNOWN = "?";
 // const END;
    public function onPlace(BlockPlaceEvent $event) {
		if($event->getItem() instanceof FlintSteel) {
			$x = $event->getBlock()->getX();
			$y = $event->getBlock()->getY();
			$Z = $event->getBlock()->getZ();
				if(new Vector3($x, $y-1, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-1, $y-1, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y+1, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y+2, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y+3, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y+1, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y+2, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y+3, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) )  {
					if (new Vector3($x, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-1, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x-2, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) and new Vector3($x+1, $y+4, $z) === Block::get(BLOCK::OBSIDIAN)) {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x-1, $y, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x-1, $y+1, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x-1, $y+2, $z), Block::get(BLOCK::PORTAL));
				}
				if(new Vector3($x, $y-1, $z) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y-1, $z-1) === Block::get(BLOCK::OBSIDIAN) 
				    and new Vector3($x, $y-1, $z-2) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y-1, $z+1) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y, $z+1) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+1, $z+1) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+2, $z+1) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+3, $z+1) === Block::get(BLOCK::OBSIDIAN)
					and new Vector3($x, $y, $z-2) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+1, $z-2) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+2, $z-2) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+3, $z-2) === Block::get(BLOCK::OBSIDIAN)
					and new Vector3($x, $y+4, $z) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+4, $z-1) === Block::get(BLOCK::OBSIDIAN) 
				    and new Vector3($x, $y+4, $z-2) === Block::get(BLOCK::OBSIDIAN) 
					and new Vector3($x, $y+4, $z+1) === Block::get(BLOCK::OBSIDIAN))  {
						$level->setBlock(new Vector3($x, $y, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y, $z-1), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+1, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+1, $z-1), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+2, $z), Block::get(BLOCK::PORTAL));
						$level->setBlock(new Vector3($x, $y+2, $z-1), Block::get(BLOCK::PORTAL));
				}
			}
		}
	}
	public function onMove(PlayerMoveEvent $event) {
		$player = $event->getPlayer();
		// if the player pass thouth the portal
		if($this->isInPortal($player)) {
			$x = $player->x;
			$y = $player->y;
			$z = $player->z;
			$level = $player->getLevel();
			// if the player is in the overworld
			if($this->getWorldType($level) === self::OVERWORLD) {
				$netherlevel = $this->getOtherLevel($level);
				$xmin = $x/8-20;
				$ymin = $y-20;
				$zmin = $z/8-20;
				$isteleported = false;
				// Checking if there is already a portal in a 21*21*21 area
				// if you dunno the nether is 8 times more small in the nether
				for($xmin = $x/8-20; $xmin <= $x/8+10; $xmin++) {
					for($ymin = $y-20; $ymin <= $y+10; $ymin++) {
						for($zmin = $z/8-20; $zmin <= $z/8+10; $zmin++) {
							// if a portal is found...
							if($this->isPortal($xmin, $ymin, $zmin, $netherlevel) and $isteleported ===! true) {
								$pz = $zmin;
								$py = $ymin;
								$px = $xmin;
								// teleporting the player thouth the portal
								$player->switchLevel($netherlevel);
								$player->teleport(new Vector3($px, $py, $pz));
								$isteleported = true;
							}
						}
					}
				} 
				// if no portal is found
				if($isteleported ===! true) {
				$xmin = $x/8-3;
				$ymin = $y;
				$zmin = $z/8-3;
				//  Clearing the area
					for($xmin = $x/8-3; $xmin <= $x/8+3; $xmin++) {
						for($ymin = $y; $ymin <= $y+4; $ymin++) {
							for($ymin = $y; $zmin <= $z/8+3; $zmin++) {
								$netherlevel->setBlock(new Vector3($min, $ymin, $zmin), Block::get(BLOCK::AIR));
							}
						}
					}
					// creating a portal
					$this->createPortal($netherlevel, new Vector3($x/8, $y, $z/8));
					$player->switchLevel($netherlevel);
					$player->teleport(new Vector3($x/8, $y, $z/8));
				}
			}
			
		elseif($this->getWorldType($level) === self::NETHER) {
				$overlevel = $this->getOtherLevel($level);
				$xmin = $x*8-20;
				$ymin = $y-20;
				$zmin = $z*8-20;
				$isteleported = false;
				// Checking if there is already a portal in a 21*21*21 area
				// if you dunno the nether is 8 times more small in the nether
				for($xmin = $x*8-20; $xmin <= $x*8+10; $xmin++) {
					for($ymin = $y-20; $ymin <= $y+10; $ymin++) {
						for($zmin = $z*8-20; $zmin <= $z*8+10; $zmin++) {
							// if a portal is found...
							if($this->isPortal($xmin, $ymin, $zmin, $overlevel) and $isteleported ===! true) {
								$pz = $zmin;
								$py = $ymin;
								$px = $xmin;
								// teleporting the player thouth the portal
								$player->switchLevel($overlevel);
								$player->teleport(new Vector3($px, $py, $pz));
								$isteleported = true;
							}
						}
					}
				} 
				// if no portal is found
				if($isteleported ===! true) {
				$xmin = $x*8-3;
				$ymin = $y;
				$zmin = $z*8-3;
				//  Clearing the area
					for($xmin = $x*8-3; $xmin <= $x*8+3; $xmin++) {
						for($ymin = $y; $ymin <= $y+4; $ymin++) {
							for($zmin = $z*8-3; $zmin <= $z*8+3; $zmin++) {
								$overlevel->setBlock(new Vector3($min, $ymin, $zmin), Block::get(BLOCK::AIR));
							}
						}
					}
					// creating a portal
					$this->createPortal($overlevel, new Vector3($x*8, $y, $z*8));
					$player->switchLevel($overlevel);
					$player->teleport(new Vector3($x*8, $y, $z*8));
				}
			}
		}
	}
	public function createPortal(Level $level, Vector3 $pos) {
		$x = $pos->x;
		$y = $pos->y;
		$z = $pos->z;
		// down side
		$level->setBlock(new Vector3($x, $y-1, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-1, $y-1, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y-1, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x+1, $y-1, $z), Block::get(BLOCK::OBSIDIAN));
		// east and west sides
		$level->setBlock(new Vector3($x+1, $y, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x+1, $y+1, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x+1, $y+2, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x+1, $y+3, $z), Block::get(BLOCK::OBSIDIAN));
	    $level->setBlock(new Vector3($x+1, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y+1, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y+2, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y+3, $z), Block::get(BLOCK::OBSIDIAN));
	    $level->setBlock(new Vector3($x-2, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		// up side
		$level->setBlock(new Vector3($x, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-1, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x-2, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		$level->setBlock(new Vector3($x+1, $y+4, $z), Block::get(BLOCK::OBSIDIAN));
		// portal blocks
		$level->setBlock(new Vector3($x, $y, $z), Block::get(BLOCK::PORTAL));
		$level->setBlock(new Vector3($x-1, $y, $z), Block::get(BLOCK::PORTAL));
		$level->setBlock(new Vector3($x, $y+1, $z), Block::get(BLOCK::PORTAL));
		$level->setBlock(new Vector3($x-1, $y+1, $z), Block::get(BLOCK::PORTAL));
		$level->setBlock(new Vector3($x, $y+2, $z), Block::get(BLOCK::PORTAL));
		$level->setBlock(new Vector3($x-1, $y+2, $z), Block::get(BLOCK::PORTAL));
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
		if(new Position($x, $y, $z, $level) === Block::get(BLOCK::PORTAL)) {
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
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
	switch(strtolower($cmd->getName())) {
		
	}
}
}