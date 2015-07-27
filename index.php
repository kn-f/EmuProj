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

function welcome(){
  Base::instance()->set('content','welcome.htm');
  $view = new View();
  echo $view->render('layout.htm');
}

function testWS(){
  $view = new View();
  echo $view->render('testws.htm');
}

function testAjax() {
    $return_array = array(); 

    // set values
    $return_array['title']         = "OK AJAX";
    $return_array['error']         = "Error 1";

    // send to browser as JSON encoded object
    echo json_encode($return_array);
}

function testAjaxGet() {

    echo 'TEST OK';
}

function testLand(){
  $view = new View();
  echo $view->render('testland.htm');
}

function testLandWS() {
    $l = new Knf\World\Land;
    $view = array();
    $empty_symbol=$l->getEmptyBlock();
    
    foreach ($l->getPlot() as $line){
        $ln = array();
        foreach ($line as $obj) {
            if ($obj === NULL) {
                $ln[] = $empty_symbol;  
            } else {
                $ln[] = $obj->getSymbol();
            }
        }
        $view[] = $ln;
    }
    
    echo json_encode($view);
}


$f3->run();
?>
