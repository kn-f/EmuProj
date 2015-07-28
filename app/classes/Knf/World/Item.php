<?php

namespace Knf\World;

class Item
{

    private $name;
    private $symbol;
    private $position = array();
    /**
     * Constructor of class Item.
     *
     * @return void
     */
    public function __construct()
    {
        // ...
        $this->name = "Item";
        $this->symbol = "*";
        $this->position = array(
            "X" => 0,
            "Y" => 0,
        );
    }

    // ...
    
    public function getSymbol() {
        return $this->symbol;
    }
    
    public function getPosition() {
        return $this->position;
    }
    
    public function setPosition($x,$y) {
        $this->position = array(
            "X" => $x,
            "Y" => $y,
        );
        
        return TRUE;
    }

}
