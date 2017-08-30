<?php
session_start();
include "config/mysql_config.php";
session_unset();
session_destroy();
header('Location: login_not.php');
