<?php
$url = $_GET['url'];

if($url){
$fp = fopen($url, "r");
$buffer = "";
// get output in buffer variable
if ($fp) {
    while(!feof($fp)) {
        $buffer .= fread($fp, 1076);
    }
}
}

print $buffer;
