<?php

// defult body if files was not added yet
$st = file_get_contents('passworddictinary.txt', true);
$body = json_encode(hashFileInput($st));


echo $body;

//takes in a String and checks for numbers
function hashFileInput($st){
    $hashinput = array();
    $file = preg_split ('/$\R?^/m', $st); 
    for($i=0; $i<count($file); $i++){
        $pass = hash("sha256", $file[$i]);
        $hashinput[$pass] = $file[$i];
    }
    return $hashinput;
}




?>