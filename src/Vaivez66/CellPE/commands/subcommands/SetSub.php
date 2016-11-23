<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 18:09
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;

class SetSub extends SubCommand{

    public function execute(Player $player, array $args){
        if(!isset($args[1])){
            $player->sendMessage($this->plugin->getMessage('cell.set.usage'));
            return;
        }
        if($this->plugin->getCellManager()->existCell($args[1]) === true){
            $player->sendMessage($this->plugin->getMessage('cell.set.exist', [$args[1]]));
            return;
        }
        $price = null;
        if(isset($args[2])){
            $price = $args[2];
        }
        $this->plugin->getSession()->setSession($player->getName(), 'pos1');
        $this->plugin->getSession()->setName($player->getName(), $args[1]);
        $this->plugin->getSession()->setPrice($player->getName(), $price);
        $player->sendMessage($this->plugin->getMessage('cell.set.pos1'));
    }

}