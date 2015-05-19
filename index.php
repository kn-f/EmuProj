<?php

$f3 = require ('lib/base.php');
$f3->config('config.ini');


function hello() {
    echo 'Hello, world!';
};

function test() {
    //$f3->set('name','world');
    $template=new Template;
    echo $template->render('template.htm');
    
    
    echo "<pre>";
    $m = new Knf\Robot\Machine;
    printf("--- NEW ---\n");
    try {
        echo $m->loadCPUInstruction("LOAD 2") ."\n";
        echo $m->loadCPUInstruction("MUL 2") ."\n";
        echo $m->loadCPUInstruction("STORE 1") ."\n";
        echo $m->loadCPUInstruction("ADD 5") ."\n";
        echo $m->loadCPUInstruction("STORE 4") ."\n";
        echo $m->loadCPUInstruction("SUB 1") ."\n";
        echo $m->loadCPUInstruction("JNZ 5") ."\n";
        echo $m->loadCPUInstruction("WRITE 1") ."\n";
        echo $m->loadCPUInstruction("READ 9") ."\n";
        echo $m->loadCPUInstruction("DIV 3") ."\n";
        
        $m->run();
        //var_dump($proc->getStatus());
    }
    catch(Exception $e) {
        echo "EXCEPTION: ".$e->getMessage();
        var_dump($m);
    }
    echo "</pre>";
}


$f3->run();
?>
