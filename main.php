<?php

include("Machine.php");

$m = new machine;

printf("--- NEW ---\n");

try {
	echo $m->loadCPUInstruction("LOAD 5") ."\n";
	echo $m->loadCPUInstruction("MUL 2") ."\n";
	echo $m->loadCPUInstruction("STORE 1") ."\n";
	echo $m->loadCPUInstruction("DIV 3") ."\n";
	echo $m->loadCPUInstruction("ADD 5") ."\n";
	echo $m->loadCPUInstruction("STORE 4") ."\n";
	echo $m->loadCPUInstruction("SUB 1") ."\n";
	echo $m->loadCPUInstruction("JNZ 6") ."\n";
	echo $m->loadCPUInstruction("WRITE 1") ."\n";
	echo $m->loadCPUInstruction("READ 9") ."\n";
	
	$m->run();

	//var_dump($proc->getStatus());
}
catch(Exception $e) {
	echo "EXCEPTION: ".$e->getMessage();
}

/* include("CPU.php");

$proc = new CPU;

var_dump($proc->getStatus());
printf("--- NEW ---\n");
try {
/*	echo $proc->execCommand("PUSH 5") ."\n";
	echo $proc->execCommand("ADD 10") ."\n";
	echo $proc->execCommand("PULL 1") ."\n"; */
	//echo $proc->execCommand("ERRR 11") ."\n"; */
/*	
	echo $proc->loadCommand("LOAD 5") ."\n";
	echo $proc->loadCommand("MUL 2") ."\n";
	echo $proc->loadCommand("STORE 1") ."\n";
	echo $proc->loadCommand("DIV 3") ."\n";
	echo $proc->loadCommand("ADD 5") ."\n";
	echo $proc->loadCommand("STORE 4") ."\n";
	echo $proc->loadCommand("SUB 1") ."\n";
	echo $proc->loadCommand("JNZ 6") ."\n";
	echo $proc->loadCommand("WRITE 1") ."\n";
	echo $proc->loadCommand("READ 9") ."\n";
	//echo $proc->loadCommand("STORE 5") ."\n";
	//echo $proc->loadCommand("ADD FF") ."\n"; // Controllare i valori non interi!

	var_dump($proc->getProgram());
	
	$proc->run();

	var_dump($proc->getStatus());
}
catch(Exception $e) {
	echo "EXCEPTION: ".$e->getMessage();
}

//print_r($proc->self::P_MEM_SIZE);

?>
