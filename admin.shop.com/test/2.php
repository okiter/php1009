<?php

echo '<pre>';

$arr = array(1,2,3,4,5);
unset($arr[2]);


var_dump($arr);

echo serialize($arr);