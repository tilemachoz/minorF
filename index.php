<?php
require_once('inc/autoload.inc.php');
date_default_timezone_set('EET');

//$uri = $_SERVER['REQUEST_URI'];
//$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
//parse_str($query, $params);
//$uri = preg_replace('/\?.*$/','',$uri);
//$uri_params = explode('/',$uri);            
//$route = array (
//'controller'=> $uri_params[1],
//'method'=> $uri_params[2],
//'params'=> $params
//);
//print_r($route);
//echo phpinfo();

$route = Router::routing($_SERVER['REQUEST_URI']);
$controller = new $route['controller'];
call_user_func_array(array($controller, $route['method']), array($route['params']));


