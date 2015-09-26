<?php
/*
 * TheWorld.php
 *
 * Name inspired by hack.sign
 * 
 */

namespace Knf\World;

class TheWorld
{
	const SPECIAL_ID = "THE_WORLD"; //special id for operations
	const WORLD_SIZE = 10; // square land
    const MAX_MACHINES = 5; // maximum number of machines per world
    const EXEC_PIPELINE_SIZE = 10; // execution pipeline size

    private $list_of_machines = array(); // array of Machine
    private $execution_pipeline = array(); //list of exeuciton pipeline (queued instructions)
    // INSTRUCTION | actor
    private $tick; //number of tick

	/**
	 * Constructor of class TheWorld.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// ...
		$this->reset();

	}
	
	public function reset()
	{
		unset($this->list_of_machines);
		$this->list_of_machines = array();
		//TBC $this->list_of_machines[self::SPECIAL_ID] = new Item(); // must create a special item for the world
        $this->execution_pipeline = array_fill(0, self::EXEC_PIPELINE_SIZE, self::SPECIAL_ID." "."UNK");
        $this->tick = 0;
	}
	
	// execution of the world
	public function run()
	{
		while (true) {
			$this->tick++;
			$this->execPipeline();
			foreach($this->list_of_machines as $i) {
				$i->execCycle();
			}
		}
	}
	
	private function execPipeline() 
	{
		foreach($this->execution_pipeline as $command)
		{
			try
			{
				$this->execCommand($command);
			}
			catch (Exception $e)
			{
				//ignore / do not execute
				//TODO implement NotValidInstruction exception
				 echo "THE WORLD - EXCEPTION: " . $e->getMessage();
			}
		}
	}	

	/**
	 * 
	 * *execCommand* function - executes commands in the pipeline(*execution_pipeline*)
	 * @param string $command
	 * @return null
	 * 
	 */
	private function execCommand($command)
	{
		$cmd = explode(' ', $command); // parse command
		
		switch($cmd[0])
		{
			case self::SPECIAL_ID: //the world
				$this->execInternalCommand($command);
			default:
				if (!array_key_exists($cmd[0], $this->list_of_machines)) //if no actor is found
				{
					throw new Exception("NOT A VALID ACTOR ". $cmd);
				}
		
				switch($cmd[1])
				{
					//PICK & PLACE
					//MOVEMENT
					case "MOVE":
					case "LOCATION":
						$this->list_of_machines[$cmd[0]];
						break;
					//Nothing
					case "UNK":
					case "NOOP":
						break; //do nothing
					default:
						// throw exception
						throw new Exception('UNKNOWN COMMAND ' . $cmd);
				}
		}

		return true;
		
	}
	// ...

}
