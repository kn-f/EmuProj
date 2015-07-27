<?php

namespace Knf\World;

class Item
{

    private $name;
    private $symbol;
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
    }

    // ...
    
    public function getSymbol() {
        return $this->symbol;
    }

}
