<?php

namespace Knf\Robot;

class Device
{
    private $name;
    private $data;
    /**
     * Constructor of class Device.
     */
    public function __construct()
    {
        $this->name = 'Dfault device';
        $this->data = 'READ';
    }

    public function read()
    {
        static $count = 0;
        static $status = false;

        $count++;

        if ($count > 5) {
            $status = true;
        }

        return array(
            'DATA' => $this->data.' / '.$count,
            'STAT' => $status,
        );
    }

    public function write($data)
    {
        $this->data = 'WRITE: '.$data;

        return true;
    }

    // ...
}
