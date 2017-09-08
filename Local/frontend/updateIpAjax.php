<?php
	$selval = $_POST['selval'];

	$str = file_get_contents("/etc/network/interfaces");
    $data = explode(PHP_EOL,$str);

    foreach($data as $key => $value){
        if($value == 'auto '.$selval){
            $return[] = substr($data[$key+2],8);
			$return[] = substr($data[$key+3],8);
            $return[] = substr($data[$key+4],8);
        }
    }
    echo $return = !empty($return) ? json_encode($return) : 0;