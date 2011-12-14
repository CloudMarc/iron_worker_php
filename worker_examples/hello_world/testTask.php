<?php

function getArgs(){
    global $argv;
    $args = array('payload' => array(), 'task_id' => null, 'dir' => null);
    foreach($argv as $k => $v){
        if ($v == '-id' && !empty($argv[$k+1])){
            $args['task_id'] = $argv[$k+1];
        }
        if ($v == '-d' && !empty($argv[$k+1])){
            $args['dir'] = $argv[$k+1];
        }
        if ($v == '-payload' && !empty($argv[$k+1]) && file_exists($argv[$k+1])){
            $args['payload'] = json_decode(file_get_contents($argv[$k+1]));
        }
    }
    return $args;
}

$args = getArgs();


echo "Hello PHP World!!!\n";
echo "at " . date('r') . "\n";

print_r($args);
