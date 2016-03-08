<?php
function autoload($class) {
    $dirs = array (
        'model',
        'controller',
        'view',
        'inc'
    );

    foreach ($dirs as $dir)
    {
        $file = sprintf('%s/%s.%s.php', $dir, strtolower($class), $dir);
        if(is_file($file)) require_once $file;
    }
}

spl_autoload_register('autoload');

