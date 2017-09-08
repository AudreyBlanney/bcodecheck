<?php

	$work = $_GET['work'];

	exec("sudo ifconfig ".$work." down");

	header("location:sys-tool.php?flag=17");