<?php

namespace Knf\Robot;

use Exception;

class CPU
{
    const FREQUENCY = 10; // Number of ops per unit of time
    const P_MEM_SIZE = 10;
    const D_MEM_SIZE = 10;

    private $regs = array();
    private $program_memory = array();
    private $data_memory = array();
    private $gpio_port = array();

    /**
     * Constructor of class CPU.
     */
    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        // Resets the CPU Status
        $this->regs = array(
            'PC' => 0, //program counter
            'ACC' => 0, //Accumulator register
        );

        $this->program_memory = array_fill(0, self::P_MEM_SIZE, 'UNK');
        $this->data_memory = array_fill(0, self::D_MEM_SIZE, 0);
        $this->gpio_port = array(
            'DEV' => 'Z-STATE', // Undetermined state - Device id
            'STAT' => 'Z-STATE', // Undetermined state - Communication status flag (true -> completed, false -> WIP)
            'DATA' => 'Z-STATE', // Undetermined state - Data of the transmission
        );

        return true;
    }

    public function getStatus()
    {
        if ($this->regs['PC'] >= self::P_MEM_SIZE) {
            $exec_status = false;
        } else {
            $exec_status = true;
        }

        // Returns the status of the machine
        return array(
            'regs' => $this->regs,
            'data_memory' => $this->data_memory,
            'GPIO_port' => $this->gpio_port,
            'execution' => $exec_status,
        );
    }

    public function getProgram()
    {
        // Returns the status of the machine
        return array(
            'regs' => $this->regs,
            'program_memory' => $this->program_memory,
        );
    }

    private function parseCommand($cmd)
    {
        //actions depends on the architecture implemented
        // Throws CPUExceptions
        $command = explode(' ', $cmd);

        return $command;
    }

    public function loadCommand($cmd)
    {
        //loads a command line in the first empty program memory space
        // throws exception if memory is full
        $position = array_search('UNK', $this->program_memory);
        if ($position === false) {
            throw new Exception('PROGRAM MEMORY FULL ' . print_r($this->program_memory));
        }

        $this->program_memory[$position] = $cmd;

        return true;
    }

    public function run()
    { //not sure this is a good method
        //Executes the program in memory

        while ($this->regs['PC'] < self::P_MEM_SIZE) {
            echo $this->regs['PC'] . ' / ' . $this->program_memory[$this->regs['PC']] . "\n";
            $this->execCommand($this->program_memory[$this->regs['PC']]);
            $this->regs['PC']++;
            usleep(1 / self::FREQUENCY * 1000000);
        }
    }

    public function step()
    {
        //Executes one command of the program in memory

        if ($this->regs['PC'] < self::P_MEM_SIZE) {
            echo $this->regs['PC'] . ' / ' . $this->program_memory[$this->regs['PC']] . "\n";
            $this->execCommand($this->program_memory[$this->regs['PC']]);
            $this->regs['PC']++;
            usleep(1 / self::FREQUENCY * 1000000); // da migliorare in teoria dovrebbe impiegare solo la differenza di tempo tra l¡ultima esecuzione e il tempo attuale
        }
    }

    public function execCommand($cmd)
    {
        //actions depends on the architecture implemented
        // Throws CPUExceptions
        $command = $this->parseCommand($cmd);

        switch ($command[0]) {
        // MATH
        case 'ADD':
            $result = $this->add($command[1]);
            break;
        case 'SUB':
            $result = $this->sub($command[1]);
            break;
        case 'MUL':
            $result = $this->multiply($command[1]);
            break;
        case 'DIV':
            $result = $this->divide($command[1]);
            break;
        // Memory
        case 'LOAD':
            $result = $this->load($command[1]);
            break;
        case 'STORE':
            $result = $this->store($command[1]);
            break;
        // Branch
        case 'JNZ':
            $result = $this->jumpNotZero($command[1]);
            break;
        // I/O
        case 'READ':
            $result = $this->readInput($command[1]);
            break;
        case 'WRITE':
            $result = $this->writeOutput($command[1]);
            break;
        // Execution
        case 'UNK':
        case 'HALT':
            $this->regs['PC'] = self::P_MEM_SIZE;
            $result = true;
            break;

        default:
            // throw exception
            throw new Exception('UNKNOWN COMMAND ' . $cmd);
        }

        return $result;
    }

    public function getGPIO()
    {
        return $this->gpio_port;
    }

    public function setGPIO($device_id, $data, $status)
    {
        $this->gpio_port['DEV'] = $device_id;
        $this->gpio_port['DATA'] = $data;
        $this->gpio_port['STAT'] = $status;

        return true;
    }

    public function invalidateGPIO()
    {
        $this->gpio_port['STAT'] = false;

        return true;
    }

    /*
     * Load and store functions
     */

    private function load($value)
    {
        $this->regs['ACC'] = intval($value);

        return true;
    }

    private function store($value)
    {
        $value = intval($value); //convert to integer
        if ($value >= 0 and $value < self::D_MEM_SIZE) { //check memory boundaries
            $this->data_memory[$value] = $this->regs['ACC'];

            return true;
        }

        throw new Exception('PULL OUT OF BOUNDARIES ' . $value);
    }

    /*
     * Jump functions
     */
    private function jumpNotZero($value)
    {
        $value = intval($value);
        if ($value < 0 or $value >= self::P_MEM_SIZE) { //check memory boundaries
            throw new Exception('JUMP OUT OF BOUNDARIES ' . $value);
        }

        if ($this->regs['ACC'] != 0) {
            $this->regs['PC'] = $value - 1;
        }

        return true;
    }

    /*
     * Math functions
     */

    private function add($value)
    {
        $this->regs['ACC'] = $this->regs['ACC'] + intval($value);

        return true;
    }

    private function sub($value)
    {
        $this->regs['ACC'] = $this->regs['ACC'] - intval($value);

        return true;
    }

    private function multiply($value)
    {
        $this->regs['ACC'] = $this->regs['ACC'] * intval($value);

        return true;
    }

    private function divide($value)
    {
        $value = intval($value);
        if ($value == 0) { //Division by 0
            throw new Exception('DIVISION BY 0!');
        }
        $this->regs['ACC'] = intval($this->regs['ACC'] / $value);

        return true;
    }

    /*
     * I/O functions
     */

    private function readInput($destination)
    {
        /*
         * Read the values returned by the device identified by the accumulator and stores them in the destination memory cell
         */
        $destination = intval($destination);

        if ($destination < 0 or $destination >= self::D_MEM_SIZE) { //check memory boundaries
            throw new Exception('INPUT - DATA MEMORY WRITE OUT OF BOUNDARIES ' . $destination);
        }

        $this->gpio_port['DEV'] = $this->regs['ACC'];

        if ($this->gpio_port['STAT'] !== true) { // data is not ready to be read
            $this->regs['PC'] = $this->regs['PC'] - 1; //repeat the instruction
        }
        $this->data_memory[$destination] = $this->gpio_port['DATA']; //copies the values from the IN port to a given memory area
        $this->gpio_port['STAT'] = false; // invalidates the data in the port

        return true;
    }

    private function writeOutput($source)
    {
        /*
         * Writes the values in the source memory area to the device identified by the accumulator
         */
        $source = intval($source);

        if ($source < 0 or $source >= self::D_MEM_SIZE) { //check memory boundaries
            throw new Exception('OUTPUT - DATA MEMORY READ OUT OF BOUNDARIES ' . $source);
        }

        $this->gpio_port['DEV'] = $this->regs['ACC'];

        $this->gpio_port['DATA'] = $this->data_memory[$source]; //copies the values from a given memory area to the output
        $this->gpio_port['STAT'] = true; //commits the data on the port

        return true;
    }

    // ...
}
