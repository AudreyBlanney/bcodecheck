<?php 
//非法字符操作
function beforeAction(){
    if (!filterOperation())
        return false;
    return true;
}

function filterOperation(){
    $getfilter="'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    $postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

    $method = strtolower($_SERVER['REQUEST_METHOD']);
    switch ($method) {
        case 'get':
            $data = $_GET;
            $filter = $getfilter;
            break;
        case 'post':
            $data = $_POST;
            $filter = $postfilter;
    }

    return deepFilter($data, $filter);
}

function deepFilter($data, $filter){
    if (empty($data))
        return true;
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (!deepFilter($value, $filter))
                return false;
        }
    } else {
        return !preg_match("/".$filter."/is", $data);
    }
    return true;
}

$befo_res = beforeAction();
if(!$befo_res){
    header("Location:{$_SERVER['PHP_SELF']}");
    exit;
}
//XSS攻击转码
 if (is_array($_GET) && !empty($_GET)) {
        foreach ($_GET as $key => &$value) {
            $value = htmlentities($value, ENT_QUOTES);
        }
    }
	
if (is_array($_POST) && !empty($_POST)) {
	foreach ($_POST as $key => &$value) {
		$value = htmlentities($value, ENT_QUOTES);
	}
}