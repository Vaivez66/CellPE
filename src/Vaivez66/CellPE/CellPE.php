<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 20/09/2016
 * Time: 17:24
 */

namespace Vaivez66\CellPE;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Vaivez66\CellPE\cell\CellManager;
use Vaivez66\CellPE\commands\MainCommand;
use Vaivez66\CellPE\listener\EventListener;
use Vaivez66\CellPE\task\ExpireTask;
use Vaivez66\CellPE\utils\Format;

use onebone\economyapi\EconomyAPI;

class CellPE extends PluginBase{

    /** @var Config */
    private $cfg;
    /** @var Config */
    private $messages;
    /** @var CellManager */
    private $cellManager;
    /** @var Format */
    private $format;
    /** @var Session */
    private $session;

    public function onEnable(){
        if($this->getServer()->getPluginManager()->getPlugin('EconomyAPI') === null){
            $this->getLogger()->critical('EconomyAPI not found. Disabling plugin...');
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->saveResource('config.yml');
        $this->saveResource('messages.yml');
        $this->cfg = new Config($this->getDataFolder() . 'config.yml', Config::YAML, []);
        $this->messages = new Config($this->getDataFolder() . 'messages.yml', Config::YAML, []);
        $this->cellManager = new CellManager($this);
        $this->format = new Format();
        $this->session = new Session($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->initCells();
        $this->initCommand();
        if($this->getValue('enable.expire') == true) {
            $this->createTask();
        }
    }

    public function initCells(){
        if(!file_exists($this->getDataFolder() . 'cells.json')){
            file_put_contents($this->getDataFolder() . 'cells.json', "[]");
        }
        $cells = json_decode(file_get_contents($this->getDataFolder() . 'cells.json', true), true);
        $this->cellManager->initCells($cells);
    }

    public function initCommand(){
        $this->getCommand('cell')->setExecutor(new MainCommand($this));
    }

    public function createTask(){
        $a = new ExpireTask($this);
        $b = $this->getServer()->getScheduler()->scheduleRepeatingTask($a, 72000);
        $a->setHandler($b);
    }

    /**
     * @param $time
     * @param $format
     * @param string $timezone
     * @return string
     */

    public function getDateTimezone($time, $format, $timezone = 'America/New_York'){
        $date = new \DateTime($time, new \DateTimeZone($timezone));
        return $date->format($format);
    }

    /**
     * @param $percent
     * @param $number
     * @return float
     */

    public function getPercentage($percent, $number){
        return ($percent * $number) / 100;
    }

    /**
     * @param $key
     * @param null $replaces
     * @return mixed
     */

    public function getValue($key, $replaces = null){
        $value = $this->cfg->get($key);
        if($replaces != null){
            foreach($replaces as $k => $v){
                $value = str_replace('{' . $k . '}', $v, $value);
            }
        }
        return $value;
    }

    /**
     * @param $key
     * @param null $replaces
     * @return string
     */

    public function getMessage($key, $replaces = null){
        $value = $this->messages->get($key);
        if($replaces != null){
            foreach($replaces as $k => $v){
                $value = str_replace('{' . $k . '}', $v, $value);
            }
        }
        return $this->format->translate($value);
    }

    /**
     * @return CellManager
     */

    public function getCellManager(){
        return $this->cellManager;
    }

    /**
     * @return Format
     */

    public function getFormat(){
        return $this->format;
    }

    /**
     * @return Session
     */

    public function getSession(){
        return $this->session;
    }

    /**
     * @param $p
     * @return bool|float
     */

    public function getMoney($p){
        return EconomyAPI::getInstance()->myMoney($p);
    }

    /**
     * @param $p
     * @param $amount
     */

    public function addMoney($p, $amount){
        EconomyAPI::getInstance()->addMoney($p, $amount);
    }

    /**
     * @param $p
     * @param $amount
     */

    public function reduceMoney($p, $amount){
        EconomyAPI::getInstance()->reduceMoney($p, $amount);
    }

    public function onDisable(){
        $this->cellManager->saveCells();
    }

}
