<?php
    require_once './includes/ExtraVals.php';
    
    require_once './includes/UserStatus.php';
    (new UserStatus)->checkUserStatus();

    if(isset($_POST['publishPost'])) {
        
        require_once './includes/BlogManager.php';
        $BM = new BlogManager();
        
        try{
            if (!$BM->addNewPost($_SESSION[ExtraVals::USER_LOGGED], "post", strip_tags($_POST["title"]), strip_tags($_POST["post"]))){
                header("Location: ./signedin.php?post=".ExtraVals::POST_ERROR);
            }else{
                header("Location: ./signedin.php?post=".ExtraVals::POST_OK);
            }
        } catch (Exception $e){
            echo "Exception: ".$e;
        }
        
        $BM->DBDisconnect();
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
            require_once './LogoutHeader.php';
        ?>
        
        <h1>New post</h1>
                        
        <form action="./addPost.php" method="POST">
            Title:
            <br>
            <input type="text" name="title">
            <br>
            <br>
            <br>
            Post:
            <br>
            <textarea rows="6" cols="50" name="post"></textarea>
            <br><br>
            <input type="submit" name="publishPost" value="Publish"/>
        </form>
    </body>
</html>
