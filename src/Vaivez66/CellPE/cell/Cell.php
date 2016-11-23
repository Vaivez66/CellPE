<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 21/09/2016
 * Time: 14:10
 */

namespace Vaivez66\CellPE\cell;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class Cell{

    /** @var string */
    private $name;
    private $date;
    private $x1;
    private $x2;
    private $y;
    private $z1;
    private $z2;
    private $level;
    private $price;
    /** @var string */
    private $owner = null;
    /** @var array */
    private $helpers = [];
    private $days;


    public function __construct($name, $date, $x1, $x2, $y, $z1, $z2, $level, $price, $days){
        $this->name = $name;
        $this->date = $date;
        $this->x1 = $x1;
        $this->x2 = $x2;
        $this->y = $y;
        $this->z1 = $z1;
        $this->z2 = $z2;
        $this->level = $level;
        $this->price = $price;
        $this->days = $days;
    }

    public function getName(){
        return $this->name;
    }

    public function getX1(){
        return $this->x1;
    }

    public function getX2(){
        return $this->x2;
    }

    public function getY(){
        return $this->y;
    }

    public function getZ1(){
        return $this->z1;
    }

    public function getZ2(){
        return $this->z2;
    }

    public function getOwner(){
        return $this->owner;
    }

    public function setOwner($owner){
        $this->owner = $owner;
    }

    public function getHelpers(){
        return $this->helpers;
    }

    public function addHelper($helper){
        $this->helpers[$helper] = $helper;
    }

    public function removeHelper($helper){
        unset($this->helpers[$helper]);
    }

    public function isHelper($name){
        if(isset($this->helpers[$name])){
            return true;
        }
        return false;
    }

    public function getDate(){
        return $this->date;
    }

    public function getLevel(){
        return $this->level;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function getDays(){
        return $this->days;
    }

    public function addDays($amount){
        $this->days += $amount;
    }

    public function reduceDays($amount){
        $this->days -= $amount;
    }

    public function getPosition(){
        return new Position($this->x1, $this->y, $this->z1, Server::getInstance()->getLevelByName($this->level));
    }

    public function teleport(Player $p){
        $p->teleport($this->getPosition());
    }

    public function teleportToCentre(Player $p){
        $p->teleport($this->getCentre());
    }

    public function isInCell(Position $pos){
        if(
            (min($this->x1, $this->x2) <= $pos->getX())
            && (max($this->x1, $this->x2) >= $pos->getX())
            && (min($this->z1, $this->z2) <= $pos->getZ())
            && (max($this->z1, $this->z2) >= $pos->getZ())
            && ($this->level == $pos->getLevel()->getName())
        ){
            return true;
        }
        return false;
    }

    public function getCentre(){
        $minX = min($this->x1, $this->x2);
        $maxX = max($this->x1, $this->x2);
        $minZ = min($this->z1, $this->z2);
        $maxZ = max($this->z1, $this->z2);
        $resultX = ($minX / 2) + ($maxX / 2);
        $resultZ = ($minZ / 2) + ($maxZ / 2);
        return new Position($resultX, $this->y, $resultZ, Server::getInstance()->getLevelByName($this->level));
    }

    public function reset(){
        $this->owner = null;
        $this->helpers = [];
    }

}