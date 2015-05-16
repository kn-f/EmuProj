<?php

namespace Knf\Robot

//include("CPU.php");
//include("Device.php");

class Machine
{
	const MAX_PERIPHERIALS = 2;
	
	private $processor; //CPU type
	private $io_devices = array();

	/**
	 * Constructor of class Machine.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// ...
		$this->processor = new CPU(); //new CPU reset status
		$this->io_devices = array(
			0 => new Device(),
		);
		
	}
	
	public function run()
	{ // executes the program in memory coordinating the interfaces
		while ($this->processor->getStatus()["execution"])
		{
				$this->processor->step();
				$gpio_status = $this->processor->getGPIO();
				
				if (is_bool($gpio_status["STAT"]) === false) { //undefined state
					continue; // ends GPIO processing
				}
				
				var_dump($gpio_status);
				
				//check if device is defined
				if (!is_a($this->io_devices[$gpio_status["DEV"]],"Device")) {
					throw new Exception("GPIO - INVALID DEVICE ACCESSED: ".$gpio_status["DEV"]);
				}
				
				if($gpio_status["STAT"] === true) // Write requested - if there's data committed to the port
				{						
					$this->io_devices[$gpio_status["DEV"]] -> write($gpio_status["DATA"]);
					$this->processor->invalidateGPIO();
				} else { // Device ouputs data on the channel continuosly
					$result = $this->io_devices[$gpio_status["DEV"]] -> read();
					$this->processor->setGPIO($gpio_status["DEV"],$result["DATA"],$result["STAT"]);
				}
		}
		
		var_dump($this->processor->getStatus());
		
	}
	
	public function loadCPUInstruction($cmd) {
		$this->processor->loadCommand($cmd);
	}
	

	// ...

}
