<?php

namespace Knf\World;

class Land
{

    const SIZE = 10; // square land
    const EMPTY_BLOCK = '.';
    
    private $plot = array();
    
    
    /**
     * Constructor of class Land.
     *
     * @return void
     */
    public function __construct()
    {
        // ...
        $this->plot = array_fill(0, self::SIZE, array_fill(0, self::SIZE, null));
        $this->plot[5][5] = new Item;
    }

    // ...
    public function getPlot(){
        return $this->plot;
    }
    
    public function getEmptyBlock() {
        return self::EMPTY_BLOCK;
    }

}
