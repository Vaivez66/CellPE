<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 26/09/2016
 * Time: 16:32
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class AdminSub extends SubCommand{

    public function execute(Player $player, array $args){
        if(!isset($args[1])){
            $player->sendMessage($this->plugin->getMessage('cell.admin.usage'));
            return;
        }
        switch($args[1]){
            case 'set':
                $usage = $this->plugin->getMessage('cell.admin.set.usage');
                if(!isset($args[2])){
                    $player->sendMessage($usage);
                    return;
                }
                if(!isset($args[3])){
                    $player->sendMessage($usage);
                    return;
                }
                if($this->plugin->getCellManager()->existCell($args[2]) === false){
                    $player->sendMessage($this->plugin->getMessage('cell.admin.set.not.exist'));
                    return;
                }
                $this->plugin->getCellManager()->getCell($args[2])->setOwner($args[3]);
                $player->sendMessage($this->plugin->getMessage('cell.admin.set.success', [$args[2], $args[3]]));
                break;
            case 'get':
                if(!isset($args[2])){
                    $player->sendMessage($this->plugin->getMessage('cell.admin.get.usage'));
                    return;
                }
                if($this->plugin->getCellManager()->existCell($args[2]) === false){
                    $player->sendMessage($this->plugin->getMessage('cell.admin.get.not.exist'));
                    return;
                }
                $cell = $this->plugin->getCellManager()->getCell($args[2]);
                $player->sendMessage($this->plugin->getMessage('cell.admin.get.success', [
                    $args[2],
                    ($cell->getOwner() === null) ? '-' : $cell->getOwner(),
                    ($cell->getHelpers() == null) ? '-' : implode(', ', $cell->getHelpers())
                ]));
                break;
            case 'reset':
                if(!isset($args[2])){
                    $player->sendMessage($this->plugin->getMessage('cell.admin.reset.usage'));
                    return;
                }
                if($this->plugin->getCellManager()->existCell($args[2]) === false){
                    $player->sendMessage($this->plugin->getMessage('cell.admin.reset.not.exist'));
                    return;
                }
                $this->plugin->getCellManager()->getCell($args[2])->reset();
                $player->sendMessage($this->plugin->getMessage('cell.admin.reset.success', [$args[2]]));
                break;
        }
    }

}