<?php

require_once './includes/ExtraVals.php';

require_once './includes/UserStatus.php';
(new UserStatus())->checkUserStatus();

if (isset($_POST["deletePostID"]) && !empty($_POST["deletePostID"])){

    require_once './includes/BlogManager.php';
    $BlogManager = new BlogManager();
    
    $postID = $_POST["deletePostID"];

    if (!$BlogManager->checkUserAuthor($postID, $_SESSION[ExtraVals::USER_LOGGED])){
        $BlogManager->DBDisconnect();
        echo "You'r note the author";
        header("Location: ./signedin.php?delete_post=".ExtraVals::AUTHOR_MISMATCH);
        exit();
    }else{
        if ($BlogManager->deletePost($postID)){
            $BlogManager->DBDisconnect();
            echo "Post deleted";
            header("Location: ./signedin.php?delete_post=".ExtraVals::DELETE_SUCCESS);
            exit();
        }else{
            $BlogManager->DBDisconnect();
            echo "Post NOT deleted";
            header("Location: ./signedin.php?delete_post=".ExtraVals::DELETE_FAIL);
            exit();
        }
    }
}else{
    header("Location: ./signedin.php?delete_post=".ExtraVals::NOT_SUBMITTED);
    exit();
}

