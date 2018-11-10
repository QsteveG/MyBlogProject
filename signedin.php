<!--
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
-->
<?php
    require_once './includes/ExtraVals.php';
    
    require_once './includes/UserStatus.php';
    (new UserStatus())->checkUserStatus();
    
    require_once './includes/BlogManager.php';
    $BlogManager = new BlogManager();
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
        
        <h1>You logged in</h1>
        
     
        <?php
            echo "<h2>Welcome, ".$_SESSION[ExtraVals::USER_LOGGED]."!</h2>";
        ?>
        
        <input type="button" onclick="location.href='./addPost.php'" value="Add post" name="add_post">
        
        <?php
            if(isset($_GET["post"])){
                $val = $_GET["post"];
                if ($val == ExtraVals::POST_ERROR){
//                    echo "<p>Error: post not saved. Try again.</p>";
                }else if($val == ExtraVals::POST_OK){
//                    echo "<p>Posted</p>";
                }
            }
            
            if (isset($_GET["edit_post"])){
                $val = $_GET["edit_post"];
                
                switch ($val) {
                    case ExtraVals::NOT_SUBMITTED:
                        echo "<p>Don't write the URL, click the button!</p>";
                    break;
                
                case ExtraVals::AUTHOR_MISMATCH:
                    echo "<p>You're not the authore or this post! You cannot edit the post</p>";
                    break;
                
                case ExtraVals::POST_ERROR :
                        echo "<p>Post edit error. Try again</p>";
                    break;
                
                case ExtraVals::POST_OK:
                        echo "<p>Post edited succesfully</p>";
                    break;
                
                    default:
                        break;
                }
            }
        ?>
        
        <?php
            $BlogManager->getPosts();
        ?>
        
    </body>
</html>

