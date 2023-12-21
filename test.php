<?php
$data = array('one' => 1, 'two' => 2, 'three' => 33);
$dataString = serialize($data);
echo $dataString;
//send elsewhere
$data = unserialize($dataString);

echo $dataString;
