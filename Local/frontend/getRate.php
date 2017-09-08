<?php
	$ajaxdata = $_POST['ajaxdata'];

	foreach ($ajaxdata as $key => $value) {
		$a = '';$b = '';$c = '';$d = '';
		exec("sudo cat /proc/net/dev | grep ".$value." | awk -F'[: ]+' '{print $3}'",$a);
	    exec("sudo cat /proc/net/dev | grep ".$value." | awk -F'[: ]+' '{print $11}'",$b);
	    $old_inbw[$value] = $a[0];
	    $old_outbw[$value] = $b[0];

	    // $inbw[$value] = ($new_inbw[$value]-$old_inbw[$value])/3;
	    // $outbw[$value] = ($new_outbw[$value]-$old_outbw[$value])/3;
	}
	sleep(5);

	foreach ($ajaxdata as $key => $value) {
		$a = '';$b = '';$c = '';$d = '';
	    exec("sudo cat /proc/net/dev | grep ".$value." | awk -F'[: ]+' '{print $3}'",$c);
	    exec("sudo cat /proc/net/dev | grep ".$value." | awk -F'[: ]+' '{print $11}'",$d);
	    $new_inbw[$value] = $c[0];
	    $new_outbw[$value] = $d[0];

	    // $inbw[$value] = ($new_inbw[$value]-$old_inbw[$value])/3;
	    // $outbw[$value] = ($new_outbw[$value]-$old_outbw[$value])/3;
	}

	foreach ($new_inbw as $new_key => $new_value) {  //入端速率
		foreach ($old_inbw as $old_key => $old_value) {
			if($new_key == $old_key){
				$inbw[$new_key] = $new_value-$old_value;
			}
		}
	}

	foreach ($new_outbw as $outbw_key => $outbw_value) {  //出端速率
		foreach ($old_outbw as $outbw_okey => $outbw_ovalue) {
			if($outbw_key == $outbw_okey){
				$outbw[$outbw_key] = $outbw_value-$outbw_ovalue;
			}
		}
	}
	$aa[] = $inbw;
	$aa[] = $outbw;
	echo json_encode($aa);

	// echo json_encode($inbw);

	// echo json_encode($outbw);
