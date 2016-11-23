<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 23/09/2016
 * Time: 8:45
 */

namespace Vaivez66\CellPE;


class Session{

    /** @var CellPE */
    private $plugin;
    private $name;
    private $price;
    /** @var array */
    private $session = [];

    public function __construct(CellPE $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param $p
     * @return string
     */

    public function searchName($p){
        return $this->name[$p];
    }

    /**
     * @param $p
     * @param $name
     */

    public function setName($p, $name){
        $this->name[$p] = $name;
    }

    /**
     * @param $p
     * @return mixed
     */

    public function searchPrice($p){
        return $this->price[$p];
    }

    /**
     * @param $p
     * @param $price
     */

    public function setPrice($p, $price){
        $this->price[$p] = $price;
    }

    /**
     * @param $name
     * @return mixed
     */

    public function getSession($name){
        if(isset($this->session[$name])){
            return $this->session[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @param $session
     */

    public function setSession($name, $session){
        $this->session[$name] = $session;
    }

    /**
     * @param $name
     */

    public function removeSession($name){
        unset($this->session[$name]);
    }

}