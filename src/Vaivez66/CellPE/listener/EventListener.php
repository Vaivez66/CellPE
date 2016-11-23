<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 22/09/2016
 * Time: 14:58
 */

namespace Vaivez66\CellPE\listener;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Position;

use Vaivez66\CellPE\CellPE;

class EventListener implements Listener{

    /** @var CellPE */
    private $plugin;
    /** @var Position[] */
    private $pos1 = [];
    /** @var Position[] */
    private $pos2 = [];

    public function __construct(CellPE $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerInteractEvent $event
     * @priority MONITOR
     */

    public function onInteract(PlayerInteractEvent $event){
        $p = $event->getPlayer();
        $block = $event->getBlock();
        if($this->plugin->getSession()->getSession($p->getName()) !== null) {
            if ($this->plugin->getSession()->getSession($p->getName()) == 'pos1') {
                $this->plugin->getSession()->setSession($p->getName(), 'pos2');
                $this->pos1[$p->getName()] = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
                $p->sendMessage($this->plugin->getMessage('cell.set.pos2'));
            }
            elseif ($this->plugin->getSession()->getSession($p->getName()) == 'pos2') {
                $this->plugin->getSession()->removeSession($p->getName());
                $this->pos2[$p->getName()] = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
                $this->plugin->getCellManager()->createCell(
                    $this->plugin->getSession()->searchName($p->getName()),
                    $this->plugin->getDateTimezone('now', 'Y-m-d'),
                    $this->pos1[$p->getName()]->getX(),
                    $this->pos2[$p->getName()]->getX(),
                    $this->pos1[$p->getName()]->getY(),
                    $this->pos1[$p->getName()]->getZ(),
                    $this->pos2[$p->getName()]->getZ(),
                    $this->pos1[$p->getName()]->getLevel()->getName(),
                    $this->plugin->getSession()->searchPrice($p->getName())
                );
                $p->sendMessage($this->plugin->getMessage('cell.set.success', [
                        $this->plugin->getCellManager()->getCell($this->plugin->getSession()->searchName($p->getName()))->getName(),
                        $this->plugin->getCellManager()->getCell($this->plugin->getSession()->searchName($p->getName()))->getPrice()]
                ));
                unset($this->pos1[$p->getName()]);
                unset($this->pos2[$p->getName()]);
            }
        }
        else{
            $pos = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
            if($cell = $this->plugin->getCellManager()->isInCell($pos)){
                if(($cell->getOwner() == $p->getName()) || ($cell->isHelper($p->getName()) === true)){
                    $event->setCancelled(false);
                }
                else{
                    $event->setCancelled(true);
                }
            }
        }
    }

    /**
     * @param PlayerMoveEvent $event
     * @priority MONITOR
     */

    public function onMove(PlayerMoveEvent $event){
        $p = $event->getPlayer();
        if($this->plugin->getValue('allow.movement.other.cell') === true){
            return;
        }
        if($cell = $this->plugin->getCellManager()->isInCell($event->getTo())){
            if(($cell->getOwner() !== null) && ($cell->getOwner() != $p->getName())){
                if($p->hasPermission('cell.move.other')){
                    return;
                }
                if($cell->isHelper($p->getName())){
                    return;
                }
                switch($p->getDirection()){
                    case 0:
                        $p->knockBack($p, 0, -1, 0, 0.5);
                        break;
                    case 1:
                        $p->knockBack($p, 0, 0, -1, 0.5);
                        break;
                    case 2:
                        $p->knockBack($p, 0, 1, 0, 0.5);
                        break;
                    case 3:
                        $p->knockBack($p, 0, 0, 1, 0.5);
                        break;
                }
                $p->sendMessage($this->plugin->getMessage('cell.move.knockback'));
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @priority MONITOR
     */

    public function onBreak(BlockBreakEvent $event){
        $p = $event->getPlayer();
        $block = $event->getBlock();
        $pos = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
        if($cell = $this->plugin->getCellManager()->isInCell($pos)){
            if(($cell->getOwner() == $p->getName()) || ($cell->isHelper($p->getName()) === true)){
                $event->setCancelled(false);
            }
            else{
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @priority MONITOR
     */

    public function onPlace(BlockPlaceEvent $event){
        $p = $event->getPlayer();
        $block = $event->getBlock();
        $pos = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
        if($cell = $this->plugin->getCellManager()->isInCell($pos)){
            if(($cell->getOwner() == $p->getName()) || ($cell->isHelper($p->getName()) === true)){
                $event->setCancelled(false);
            }
            else{
                $event->setCancelled(true);
            }
        }
    }

}