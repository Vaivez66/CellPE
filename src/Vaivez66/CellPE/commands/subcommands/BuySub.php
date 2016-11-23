<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 17:08
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class BuySub extends SubCommand{

    public function execute(Player $player, array $args){
        if($cell = $this->plugin->getCellManager()->isInCell($player->getPosition())){
            if($cell->getOwner() == $player->getName()){
                $player->sendMessage($this->plugin->getMessage('cell.buy.in.own.cell'));
                return;
            }
            if($cell->getOwner() != null){
                $player->sendMessage($this->plugin->getMessage('cell.buy.owned', [$cell->getOwner()]));
                return;
            }
            if(($this->plugin->getMoney($player) - $cell->getPrice()) < 0){
                $player->sendMessage($this->plugin->getMessage('cell.buy.not.enough.money', [$cell->getPrice()]));
                return;
            }
            $this->plugin->reduceMoney($player, $cell->getPrice());
            $cell->setOwner($player->getName());
            $player->sendMessage($this->plugin->getMessage('cell.buy.success', [$cell->getName()]));
        }
        else {
            $player->sendMessage($this->plugin->getMessage('cell.buy.not.in.cell'));
        }
    }

}