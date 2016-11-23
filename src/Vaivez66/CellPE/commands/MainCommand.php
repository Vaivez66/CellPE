<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/09/2016
 * Time: 17:01
 */

namespace Vaivez66\CellPE\commands;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use Vaivez66\CellPE\CellPE;
use Vaivez66\CellPE\commands\subcommands\AddHelperSub;
use Vaivez66\CellPE\commands\subcommands\AdminSub;
use Vaivez66\CellPE\commands\subcommands\BuySub;
use Vaivez66\CellPE\commands\subcommands\CheckSub;
use Vaivez66\CellPE\commands\subcommands\DeleteSub;
use Vaivez66\CellPE\commands\subcommands\RemoveHelperSub;
use Vaivez66\CellPE\commands\subcommands\SellSub;
use Vaivez66\CellPE\commands\subcommands\SetSub;
use Vaivez66\CellPE\commands\subcommands\SubCommand;
use Vaivez66\CellPE\commands\subcommands\TeleportSub;
use Vaivez66\CellPE\commands\subcommands\UpgradeSub;

class MainCommand implements CommandExecutor{

    /** @var CellPE */
    private $plugin;
    /** @var SubCommand[] */
    private $subCommands = [];

    public function __construct(CellPE $plugin){
        $this->plugin = $plugin;
        $this->initSubCommands();
    }

    public function initSubCommands(){
        $this->registerSubCommand(new AddHelperSub($this->plugin, 'addhelper', 'cell.addhelper', 'Add a helper for your cell'));
        $this->registerSubCommand(new AdminSub($this->plugin, 'admin', 'cell.admin', 'Manage cells'));
        $this->registerSubCommand(new BuySub($this->plugin, 'buy', 'cell.buy', 'Buy a cell you\'re standing on'));
        $this->registerSubCommand(new CheckSub($this->plugin, 'check', 'cell.check', 'Check a cell you\'re standing on'));
        $this->registerSubCommand(new DeleteSub($this->plugin, 'delete', 'cell.delete', 'Delete a cell'));
        $this->registerSubCommand(new RemoveHelperSub($this->plugin, 'removehelper', 'cell.removehelper', 'Remove one of your helpers'));
        $this->registerSubCommand(new SellSub($this->plugin, 'sell', 'cell.sell', 'Sell one of your cells'));
        $this->registerSubCommand(new SetSub($this->plugin, 'set', 'cell.set', 'Set a cell location'));
        $this->registerSubCommand(new TeleportSub($this->plugin, 'teleport', 'cell.teleport', 'Teleport to one of your cells'));
        $this->registerSubCommand(new UpgradeSub($this->plugin, 'upgrade', 'cell.upgrade', 'Upgrade your cell'));
    }

    public function registerSubCommand(SubCommand $subCommand){
        $this->subCommands[$subCommand->getName()] = $subCommand;
    }

    public function sendHelp(Player $sender){
        $sender->sendMessage($this->plugin->getMessage('cell.help.header'));
        foreach($this->subCommands as $subCommand){
            if($subCommand->isAllowed($sender)){
                $sender->sendMessage($this->plugin->getMessage('cell.help', [
                    'sub' => $subCommand->getName(),
                    'desc' => $subCommand->getDescription()
                ]));
            }
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch ($cmd->getName()) {
            case 'cell':
                if (!$sender instanceof Player) {
                    $sender->sendMessage($this->plugin->getMessage('cell.in.game'));
                    return;
                }
                if (!isset($args[0])) {
                    $this->sendHelp($sender);
                    return;
                }
                if (!isset($this->subCommands[$args[0]])) {
                    $this->sendHelp($sender);
                    return;
                }
                $subCommand = $this->subCommands[$args[0]];
                if ($subCommand->isAllowed($sender) === false) {
                    $sender->sendMessage($this->plugin->getMessage('cell.' . $subCommand->getName() . '.no.permission'));
                    return;
                }
                $subCommand->execute($sender, $args);
                break;
        }
    }

}