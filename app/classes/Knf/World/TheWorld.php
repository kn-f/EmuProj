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
    const MAX_ITEMS = 5; // maximum number of machines per world
    const EXEC_PIPELINE_SIZE = 10; // execution pipeline size

    private $list_of_items = array(); // array of Item
    private $execution_pipeline = array(); //list of exeuciton pipeline (queued instructions)
	private $world = array(); //sparse array
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
		//unset($this->list_of_items);
		$this->list_of_items = array();
		//TBC $this->list_of_machines[self::SPECIAL_ID] = new Item(); // must create a special item for the world
        $this->execution_pipeline = array_fill(0, self::EXEC_PIPELINE_SIZE, self::SPECIAL_ID." "."UNK");
        $this->tick = 0;
        $this->world = array();
	}
	
	/*
	 * 
	 * name: queueCommand
	 * Queues a command in the EXEC_PIPELINE up to the maximum allowed size
	 * throws exception if memory is full
	 * @param string $command Command with parameters to be queued
	 * @return null
	 * 
	 */
	public function queueCommand($cmd)
	{
        $position = array_search('UNK', $this->execution_pipeline);
        if ($position === false) {
            throw new Exception('EXECUTION PIPELINE FULL ' . print_r($this->execution_pipeline));
        }

        $this->execution_pipeline[$position] = $cmd;

        //return true;
	}
	
	/*
	 * 
	 * name: unknown
	 * @param
	 * @return
	 * 
	 */	
	public function registerItem($Item, $destination)
	{
		if (size($this->list_of_items)>= self::MAX_ITEMS)
		{
			throw new Exception("TOO MANY ITEMS ".print_r($Item));
		}
		
		if ($this->move($Item,$destination) == false)
		{
			throw new Exception("DESTINATION IN USE ".print_r($Item));
		}
		
		array_push($this->list_of_items,$Item);
		
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
		//invalidates the execution pipeline once all commands have been executed
		 $this->execution_pipeline = array_fill(0, self::EXEC_PIPELINE_SIZE, self::SPECIAL_ID." "."UNK");
	}	

	/**
	 * execCommand
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
					throw new Exception("NOT A VALID ACTOR ". $command);
				}
		
				switch($cmd[1])
				{
					//PICK & PLACE
					//MOVEMENT
					case "MOVE": // ID - MOVE - x,y
						$this->move($this->list_of_machines[$cmd[0]],$cmd[2]);
					case "LOCATION":
						$this->list_of_machines[$cmd[0]]->locate();
						break;
					//Nothing
					case "UNK":
					case "NOOP":
						break; //do nothing
					default:
						// throw exception
						throw new Exception('UNKNOWN COMMAND ' . $command);
				}
		}

		return true;
		
	}
	// ...
	
	/*
	 * 
	 * name: execInternalCommand
	 * @param string $command
	 * @return
	 * 
	 */
	private function execInternalCommand($command)
	{
		$cmd = explode(' ', $command); // parse command
		
		switch($cmd[1])
		{
			case "UNK": //do nothing
			case "STOP": //halt executions
			case "RESET": //resets the machine
				break;
			default:
				// throw exception
				throw new Exception('UNKNOWN COMMAND ' . $command);
		}
		
	}
	
	/*
	 * 
	 * name: move
	 * moves an *$Item* to its *$destination* if not possible does not change the $Item state -- NO MESSAGE??  
	 * @param object $Item an item, string $destination destination in "x,y" format
	 * @return null
	 * 
	 */
	private function move($Item,$destination)
	{
		$dst = explode(',', $destination); // parse destination
		// TODO check $destinaiton format etc.
		if (!isset($this->world[$dst[0]][$dst[1]])) //empty destination
		{
			$position = $Item->getPosition();
			$Item->setPosition($dst[0],$dst[1]);
			unset($this->world[$x][$y]);
			$this->world[$dst[0]][$dst[1]] = $Item->getSymbol();
			
			return true;
		}
		
		return false;
	}

}
