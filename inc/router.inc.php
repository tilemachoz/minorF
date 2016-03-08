<?php

class Router {


    private static $routing_table = array(
        '/'=>array(
            'controller'=>'home',
            'method'=>'index',
            'params'=>array()
            )
    );

    public static function routing ($uri) {
        if (isset(self::$routing_table[$uri])) //array_key_exist('key', $array)
        {
            return self::$routing_table[$uri];
        }
        else
        {
            $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            parse_str($query, $params);
            
            $uri = preg_replace('/\?.*$/','',$uri);
            $uri_params = explode('/',$uri);            
            if (class_exists($uri_params[1], true))
            {
                $controller = new $uri_params[1];
            }
            else {die("Error 404 page not found");}
            if (!method_exists($controller, $uri_params[2])) die("Error 404 page not found");
            $route = array (
                'controller'=> $uri_params[1],
                'method'=> $uri_params[2],
                'params'=> $params
            );
            return $route;
        }
    }

}
