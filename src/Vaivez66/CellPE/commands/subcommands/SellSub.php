<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 18:08
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class SellSub extends SubCommand{

    public function execute(Player $player, array $args){
        if(!isset($args[1])){
            $player->sendMessage($this->plugin->getMessage('cell.sell.usage'));
            return;
        }
        if($this->plugin->getCellManager()->existCell($args[1]) === false){
            $player->sendMessage($this->plugin->getMessage('cell.sell.not.exist'));
            return;
        }
        $cell = $this->plugin->getCellManager()->getCell($args[1]);
        if($cell->getOwner() != $player->getName()){
            $player->sendMessage($this->plugin->getMessage('cell.sell.not.own.cell'));
            return;
        }
        $this->plugin->getCellManager()->getCell($args[1])->reset();
        $this->plugin->addMoney($player, $percentage = $this->plugin->getPercentage($this->plugin->getValue('sell.percent'), $cell->getPrice()));
        $player->sendMessage($this->plugin->getMessage('cell.sell.success', [$args[1], $percentage]));
    }

}