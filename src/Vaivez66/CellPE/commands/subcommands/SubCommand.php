<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 17:03
 */

namespace Vaivez66\CellPE\commands\subcommands;

use pocketmine\Player;
use pocketmine\command\CommandSender;

use Vaivez66\CellPE\CellPE;

abstract class SubCommand{

    /** @var CellPE */
    public $plugin;
    /** @var string */
    private $name;
    /** @var string */
    private $permission;
    /** @var string */
    private $description;

    public function __construct(CellPE $plugin, $name, $permission, $description){
        $this->plugin = $plugin;
        $this->name = $name;
        $this->permission = $permission;
        $this->description = $description;
    }

    public function getPlugin(){
        return $this->plugin;
    }

    public function getName(){
        return $this->name;
    }

    public function getPermission(){
        return $this->permission;
    }

    public function getDescription(){
        return $this->description;
    }

    public function isAllowed(Player $player){
        if($player->hasPermission($this->permission)){
            return true;
        }
        return false;
    }

    abstract function execute(Player $player, array $args);

}