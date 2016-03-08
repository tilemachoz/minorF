<?php

class Helper {


    public static function sessionValidate() {
        if (!session_id()) session_start();
        $user = new Users();
        if (!empty($_SESSION['user']) && !empty($_SESSION['hash']) && $user->validSession($_SESSION['user'], $_SESSION['hash'])) {
            return true;
        }
        return false;
    }
    
}


?>
