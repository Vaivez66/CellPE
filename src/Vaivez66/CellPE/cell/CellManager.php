<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 21/09/2016
 * Time: 14:10
 */

namespace Vaivez66\CellPE\cell;

use pocketmine\Player;
use pocketmine\level\Position;

use Vaivez66\CellPE\CellPE;

class CellManager{

    /** @var CellPE */
    private $plugin;
    /** @var Cell[] */
    private $cells = [];

    public function __construct(CellPE $plugin){
        $this->plugin = $plugin;
    }

    public function initCells($cells){
        if($cells == null){
            return;
        }
        foreach($cells as $key => $value){
            $this->createCell($key, $value['date'], $value['x1'], $value['x2'], $value['y'], $value['z1'], $value['z2'], $value['level'], $value['price'], $value['days']);
            if($value['owner'] != null){
                $this->getCell($key)->setOwner($value['owner']);
            }
            if($value['helpers'] != null){
                $helpers = (array) $value['helpers'];
                foreach($helpers as $helper){
                    $this->getCell($key)->addHelper($helper);
                }
            }
        }
    }

    public function saveCells(){
        if($this->cells == null){
            file_put_contents($this->plugin->getDataFolder() . 'cells.json', "[]");
            return;
        }
        $cells = [];
        foreach($this->cells as $key => $value){
            $cells[$key] = [
                'date' => $value->getDate(),
                'x1' => $value->getX1(),
                'x2' => $value->getX2(),
                'y' => $value->getY(),
                'z1' => $value->getZ1(),
                'z2' => $value->getZ2(),
                'level' => $value->getLevel(),
                'price' => $value->getPrice(),
                'days' => $value->getDays(),
                'owner' => $value->getOwner(),
                'helpers' => $value->getHelpers()
            ];
        }
        file_put_contents($this->plugin->getDataFolder() . 'cells.json', json_encode($cells));
    }

    /**
     * @param Position $pos
     * @return bool|null|Cell
     */

    public function isInCell(Position $pos){
        if($this->cells == null){
            return null;
        }
        foreach($this->cells as $cell){
            if($cell->isInCell($pos)){
                return $cell;
            }
        }
        return false;
    }

    /**
     * @param Position $pos
     * @param Player $p
     * @return bool|null
     */

    public function isInOwnCell(Position $pos, Player $p){
        if($this->cells == null){
            return null;
        }
        foreach($this->cells as $cell){
            if($cell->isInCell($pos)){
                if(($cell->getOwner() != null) && (strtolower($cell->getOwner()) == strtolower($p->getName()))){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $name
     * @param $date
     * @param $x1
     * @param $x2
     * @param $y
     * @param $z1
     * @param $z2
     * @param $level
     * @param $price
     * @param $days
     */

    public function createCell($name, $date, $x1, $x2, $y, $z1, $z2, $level, $price, $days = null){
        if(($price == null) || ($price == '')){
            $price = $this->plugin->getValue('default.price');
        }
        if($days == null){
            $days = (int) $this->plugin->getValue('expire.days');
        }
        $this->cells[$name] = new Cell($name, $date, $x1, $x2, $y, $z1, $z2, $level, $price, $days);
    }

    /**
     * @return Cell[]
     */

    public function getCells(){
        return $this->cells;
    }

    /**
     * @param $name
     * @return Cell
     */

    public function getCell($name){
        return $this->cells[$name];
    }

    /**
     * @param $name
     */

    public function delCell($name){
        unset($this->cells[$name]);
    }

    /**
     * @param $name
     * @return bool
     */

    public function existCell($name){
        if(isset($this->cells[$name])){
            return true;
        }
        return false;
    }


}