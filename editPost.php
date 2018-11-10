<?php

require_once './includes/ExtraVals.php';
    
require_once './includes/UserStatus.php';
(new UserStatus())->checkUserStatus();

require_once './includes/BlogManager.php';
$BlogManager = new BlogManager();
$postID = -1;

if (isset($_POST["editPostID"]) && !empty($_POST["editPostID"])){

    if (!$BlogManager->checkUserAuthor($_POST["editPostID"], $_SESSION[ExtraVals::USER_LOGGED])){
        echo "Your NOT the Author of this post!";
        $BlogManager->DBDisconnect();
        header("Location: ./signedin.php?edit_post=".ExtraVals::AUTHOR_MISMATCH);
        exit();
    }else{
//        echo "Your the Author of this post!";
        $postID = $_POST["editPostID"];
    }

}else if (isset($_POST["editThisPost"]) && !empty($_POST["editThisPost"])){
//    echo "Edit post clicked and has values";

//    echo $_POST["postDate"];
    
        
    if ($BlogManager->editPost($_POST["title"], $_POST["post"], $_POST["editThisPost"], $_POST["postDate"])){
//        echo "Edit post OK";
        $BlogManager->DBDisconnect();
        header("Location: ./signedin.php?edit_post=".ExtraVals::EDIT_SUCCESS);
    }else{
//        echo "Edit post FAIL";
        $BlogManager->DBDisconnect();
        header("Location: ./signedin.php?edit_post=".ExtraVals::EDIT_FAIL);
    }
}else{
    $BlogManager->DBDisconnect();
//    echo "EditPostID not found";
    header("Location: ./signedin.php?edit_post=".ExtraVals::NOT_SUBMITTED);
    exit();
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
            require_once './LogoutHeader.php';
        ?>
        
        <h1>Edit post</h1>
                        
        <form action="./editPost.php" method="POST">
            Date:
            <br>
            <input id="date" type="date" name="postDate" value="<?php
                if ($postID != -1){
                    $date = $BlogManager->getPostDate($postID);
                    if ($date != NULL){
                        echo date("Y-d-m", $date);
//                        echo date("Y-d-m");
                    }else{
                        echo date("Y-d-m");
                    }
                }else{
                    echo date("Y-d-m");
                }
            ?>">
            
            <br>
            <br>
            Title:
            <br>
            <input type="text" name="title" value="<?php 
                if ($postID != -1){
                    $title = $BlogManager->getPostTitleByID($postID);
                    if ($title != NULL){
                        echo $title;
                    }else{
                        echo "Title not retrieved from DB";
                    }
                }else{
                    echo "No title found (postID = -1)";
                }
            ?>">
            <br>
            <br>
            <br>
            Post:
            <br>
            <textarea rows="6" cols="50" name="post"><?php
                if ($postID != -1){
                    $content = $BlogManager->getPostContentByID($postID);
                    if ($content != NULL){
                        echo $content;
                    }else{
                        echo "Content not retrieved from DB";
                    }
                }else{
                    echo "Content not found (postID = -1)";
                }
            ?>
            </textarea>
            <br><br>
            <button type="submit" name="editThisPost" value="<?php
                echo $postID;
            ?>"/>Commit</button>
        </form>
        
        <br>
        <input type="button" onclick="location.href='./signedIn.php'" value="Cancel" name="cancel">
        
    </body>
    
</html>

