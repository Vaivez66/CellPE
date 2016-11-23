<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 18:09
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class UpgradeSub extends SubCommand{

    public function execute(Player $player, array $args){
        if(!isset($args[1])){
            $player->sendMessage($this->plugin->getMessage('cell.upgrade.usage'));
            return;
        }
        if($this->plugin->getCellManager()->existCell($args[1]) === false){
            $player->sendMessage($this->plugin->getMessage('cell.upgrade.not.exist'));
            return;
        }
        $cell = $this->plugin->getCellManager()->getCell($args[1]);
        if($cell->getOwner() != $player->getName()){
            $player->sendMessage($this->plugin->getMessage('cell.upgrade.not.own.cell'));
            return;
        }
        if(($this->plugin->getMoney($player) - ($percentage = $this->plugin->getPercentage($this->plugin->getValue('upgrade.percent'), $cell->getPrice()))) < 0){
            $player->sendMessage($this->plugin->getMessage('cell.upgrade.not.enough.money'));
            return;
        }
        $this->plugin->reduceMoney($player, $percentage);
        $cell->addDays($days = $this->plugin->getValue('upgrade.days'));
        $player->sendMessage($this->plugin->getMessage('cell.upgrade.success', [$cell->getName(), $days, $percentage]));
    }

}
