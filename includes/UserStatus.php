<?php

class UserStatus{
    
    function checkUserStatus(){
        session_start();
        if (!isset($_SESSION[ExtraVals::IS_LOGGED_IN]) || !isset($_SESSION[ExtraVals::USER_LOGGED])){
                session_destroy();
                header("Location: ./index.php?logged_user=".ExtraVals::SESSION_PARAMS_NOT_FOUND);
                exit();
        }
    }
}

