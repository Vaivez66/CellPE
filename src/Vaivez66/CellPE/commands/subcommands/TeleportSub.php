<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 26/09/2016
 * Time: 13:23
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class TeleportSub extends SubCommand{

    public function execute(Player $player, array $args){
        if(!isset($args[1])){
            $player->sendMessage($this->plugin->getMessage('cell.teleport.usage'));
            return;
        }
        if($this->plugin->getCellManager()->existCell($args[1]) === false){
            $player->sendMessage($this->plugin->getMessage('cell.teleport.not.exist', [$args[1]]));
            return;
        }
        if($this->plugin->getCellManager()->getCell($args[1])->getOwner() != $player->getName()){
            if($player->hasPermission('cell.teleport.other')){
                $this->plugin->getCellManager()->getCell($args[1])->teleportToCentre($player);
                $player->sendMessage($this->plugin->getMessage('cell.teleport.success', [$args[1]]));
                return;
            }
            $player->sendMessage($this->plugin->getMessage('cell.teleport.not.own.cell'));
            return;
        }
        $this->plugin->getCellManager()->getCell($args[1])->teleportToCentre($player);
        $player->sendMessage($this->plugin->getMessage('cell.teleport.success', [$args[1]]));
    }

}