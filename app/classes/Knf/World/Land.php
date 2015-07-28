<?php

namespace Knf\World;

class Land
{

    const SIZE = 10; // square land
    const EMPTY_BLOCK = '.';
    
    private $listItems = array();
    
    
    /**
     * Constructor of class Land.
     *
     * @return void
     */
    public function __construct()
    {
        // ...
        //$this->plot = array_fill(0, self::SIZE, array_fill(0, self::SIZE, null));
        //$this->plot[self::SIZE/2][self::SIZE/2] = new Item;
        $i = new Item;
        $i->setPosition(0,13);
        
        array_push($this->listItems, $i);
    }

    // ...
    public function getPlot(){
        $plot = array_fill(0, self::SIZE, array_fill(0, self::SIZE, null));
        
        foreach ($this->listItems as $i) {
            $pos = $i->getPosition();
            $plot[$pos["X"]][$pos["Y"]] = $i;
        }
        return $plot;
    }
    
    public function getEmptyBlock() {
        return self::EMPTY_BLOCK;
    }
    
    public function moveItem($itemId, $x,$y) {
        
        // foreach ($this->listItems as $i) {
        //    if ($item)
        $this->listItems[$itemId]->setPosition($x,$y);
        //    $pos = $i->getPosition();
        //    $plot[$pos["X"]][$pos["Y"]] = $i;
        //}
        return true;

    }

}
