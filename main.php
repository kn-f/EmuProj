<?php

include("CPU.php");

$proc = new CPU;

var_dump($proc->getStatus());
printf("--- NEW ---\n");
try {
/*	echo $proc->execCommand("PUSH 5") ."\n";
	echo $proc->execCommand("ADD 10") ."\n";
	echo $proc->execCommand("PULL 1") ."\n"; */
	//echo $proc->execCommand("ERRR 11") ."\n";
	
	echo $proc->loadCommand("LOAD 5") ."\n";
	echo $proc->loadCommand("MUL 2") ."\n";
	echo $proc->loadCommand("STORE 1") ."\n";
	echo $proc->loadCommand("DIV 0") ."\n";
	echo $proc->loadCommand("ADD 5") ."\n";
	echo $proc->loadCommand("STORE 6") ."\n";
	echo $proc->loadCommand("ADD -1") ."\n";
	echo $proc->loadCommand("JNZ 5") ."\n";
	echo $proc->loadCommand("STORE 3") ."\n";
	echo $proc->loadCommand("LOAD 6") ."\n";
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
