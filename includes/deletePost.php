<?php
    require_once './ExtraVals.php';
   
    require_once './UserStatus.php';
    (new UserStatus())->checkUserStatus();
    
    if (isset($_POST["deletePostID"]) && !empty($_POST["deletePostID"])){
        if (!$BlogManager->checkUserAuthor($_POST["editPostID"], $_SESSION[ExtraVals::USER_LOGGED])){
            $BlogManager->DBDisconnect();
            header("Location: ./signedin.php?delete_post=".ExtraVals::AUTHOR_MISMATCH);
            exit();
        }else{
            //query to delete post and redirect to editPost with a proper message
            //(OK or FAIL
        }
    }

