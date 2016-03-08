<?php

class View {
    private $template;

    public function __construct($template, $file='index'){
        $file = 'view/templates/'.$template.'/'.$file.'.php';
        if (file_exists($file))
        {
            $this->template = $file;
        }
        else
        {
            throw new Exception('Template ' . $file . ' not found!');
        }
    }

    public function render ($params = array()) {
        extract($params);
        $template_path = dirname($this->template);
        include($this->template);
    }
}
