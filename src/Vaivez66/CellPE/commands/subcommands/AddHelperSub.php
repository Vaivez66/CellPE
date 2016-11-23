<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 27/09/2016
 * Time: 13:43
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class AddHelperSub extends SubCommand{

    public function execute(Player $player, array $args){
        $usage = $this->plugin->getMessage('cell.addhelper.usage');
        if(!isset($args[1])){
            $player->sendMessage($usage);
            return;
        }
        if(!isset($args[2])){
            $player->sendMessage($usage);
            return;
        }
        if($this->plugin->getCellManager()->existCell($args[1]) === false){
            $player->sendMessage($this->plugin->getMessage('cell.addhelper.not.exist'));
            return;
        }
        $cell = $this->plugin->getCellManager()->getCell($args[1]);
        if($cell->getOwner() != $player->getName()){
            $player->sendMessage($this->plugin->getMessage('cell.addhelper.not.own.cell'));
            return;
        }
        if($cell->isHelper($args[2])){
            $player->sendMessage($this->plugin->getMessage('cell.addhelper.already.helper', [$args[2]]));
            return;
        }
        if($player->getName() == $args[2]){
            $player->sendMessage($this->plugin->getMessage('cell.addhelper.is.owner'));
            return;
        }
        $cell->addHelper($args[2]);
        $player->sendMessage($this->plugin->getMessage('cell.addhelper.success', [$args[2], $args[1]]));
    }

}