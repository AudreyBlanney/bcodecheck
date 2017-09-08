<?php
	
	$work = $_GET['work'];
	exec("sudo ifconfig ".$work." up");

	header("location:sys-tool.php?flag=18");