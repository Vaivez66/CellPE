<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 23/09/2016
 * Time: 15:42
 */

namespace Vaivez66\CellPE\task;

use pocketmine\scheduler\PluginTask;

use Vaivez66\CellPE\CellPE;

class ExpireTask extends PluginTask{

    /** @var CellPE  */
    private $plugin;

    public function __construct(CellPE $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($tick){
        if($this->plugin->getCellManager()->getCells() != null) {
            foreach($this->plugin->getCellManager()->getCells() as $key => $value) {
                $firstDate = date_create($value->getDate());
                $secondDate = date_create($this->plugin->getDateTimezone('now', "Y-m-d"));
                $interval = date_diff($firstDate, $secondDate);
                if ($interval->days > $value->getDays()) {
                    $value->reset();
                }
            }
        }
    }

}