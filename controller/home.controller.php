<?php

class Home
{

    public function __construct() {
         if (!session_id()) session_start();
    }
    
    public function index() {
        $home = new View('template_name');
        $home->render(array());
    }
    
    public function forbidden() {
        //print_r($_SESSION);
        $home = new View('template_name', 'forbidden');
        $home->render(array());
    }
    
    public function keepSessionAlive() {
        // does not need to do nothing
    }
}
