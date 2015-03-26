<?php
/*
 * CPU.php
 * 
 * Copyright 2015 knF <knf@knf-HP-Pavilion-dv7-Notebook-PC>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */



class CPU
{
	const FREQUENCY = 10; // Number of ops per unit of time
	const P_MEM_SIZE = 10;
	const D_MEM_SIZE = 10;
	
	
	private $regs = array();
	private $program_memory = array();
	private $data_memory = array();
	
	/**
	 * Constructor of class CPU.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->reset();
	}
	
	public function reset() 
	{
		// Resets the CPU Status
		$this->regs = array(
			"PC" => 0, //program counter
			"ACC" => 0, //Accumulator register
		);
		
		$this->program_memory = array_fill(0,self::P_MEM_SIZE,'UNK');
		$this->data_memory = array_fill(0,self::D_MEM_SIZE,0);
		
		return true;
	}
	
	public function getStatus() 
	{
		// Returns the status of the machine
		return array(
			"regs" => $this->regs,
			"data_memory" => $this->data_memory,
		);
	}
	
	public function getProgram() 
	{
		// Returns the status of the machine
		return array(
			"regs" => $this->regs,
			"program_memory" => $this->program_memory,
		);
	}

	private function parseCommand($cmd)
	{
		//actions depends on the architecture implemented
		// Throws CPUExceptions
		$command = explode(" ",$cmd);
		
		return $command;
		
	}

	public function loadCommand($cmd) {
		//loads a command line in the first empty program memory space
		// throws exception if memory is full
		$position = array_search("UNK",$this->program_memory);
		if ($position === false) {
			throw new Exception("PROGRAM MEMORY FULL ".print_r($this->program_memory));
		}
		
		$this->program_memory[$position] = $cmd;
		return true;
		
	}
	
	public function run() {
		//Executes the program in memory

		while ($this->regs["PC"] < self::P_MEM_SIZE) {
			echo $this->regs["PC"]." / ".$this->program_memory[$this->regs["PC"]]."\n";
			$this->execCommand($this->program_memory[$this->regs["PC"]]);
			$this->regs["PC"]++;
			usleep(1/self::FREQUENCY*1000000);
		}
		
	}
	
	
	public function execCommand($cmd)
	{
		//actions depends on the architecture implemented
		// Throws CPUExceptions
		$command = $this->parseCommand($cmd);
		
		switch ($command[0]) {
			case "ADD":
				$result = $this->add($command[1]);
				break;
			case "SUB":
				$result = $this->add($command[1]);
				break;
			case "MUL":
				$result = $this->multiply($command[1]);
				break;
			case "DIV":
				$result = $this->divide($command[1]);
				break;
			case "LOAD":
				$result = $this->load($command[1]);
				break;
			case "STORE":
				$result = $this->store($command[1]);
				break;
			case "JNZ":
				$result = $this->jumpNotZero($command[1]);
				break;
			case "UNK":
			case "HALT":
				$this->regs["PC"] = self::P_MEM_SIZE;
				$result = true;
				break;
			
			default:
				// throw exception
				throw new Exception("UNKNOWN COMMAND ".$cmd);
		}
		
		return $result;
		
	}
	
	/*
	 * Load and store functions
	 */
	 
	private function load($value) 
	{
		$this->regs["ACC"] = intval($value);
		
		return true;
	}
	
	private function store($value) 
	{
		$value = intval($value);  //convert to integer
		if ($value >=0 and $value < self::D_MEM_SIZE) { //check memory boundaries
			$this->data_memory[$value] = $this->regs["ACC"];
			return true;
		}
		
		throw new Exception("PULL OUT OF BOUNDARIES ".$value);
		
	}
	
	/*
	 * Jump functions
	 */
	private function jumpNotZero($value) 
	{
		$value = intval($value);
		if ($value <0 or $value >= self::P_MEM_SIZE) { //check memory boundaries
			throw new Exception("JUMP OUT OF BOUNDARIES ".$value);
		}

		if ($this->regs["ACC"] <> 0) {
			$this->regs["PC"] = $value - 1;
		}
			return true;		
		
	}						

	/*
	 * Math functions
	 */
	
	private function add($value) 
	{
		$this->regs["ACC"] = $this->regs["ACC"] + intval($value);
		
		return true;
	}
	
	private function multiply($value) 
	{
		$this->regs["ACC"] = $this->regs["ACC"] * intval($value);
		
		return true;
	}
	
	private function divide($value) 
	{
		$value = intval($value);
		if ($value == 0) { //Division by 0
			throw new Exception("DIVISION BY 0!");
		}
		$this->regs["ACC"] = $this->regs["ACC"] / intval($value);
		
		return true;
	}
	// ...

}
