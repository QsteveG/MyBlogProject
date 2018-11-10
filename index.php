<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
    require_once './includes/ExtraVals.php';
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body> 
        
        <form action="includes/login.php" method="POST">
            Username <input type="text" name="username" placeholder="Username">
            <br>
            <br>
            Password <input type="password" name="password" placeholder="Password">
            <br>
            <br>
            <button type="submit" name="submit">Login</button>
        </form>
        
        <?php
            if (isset($_GET["login"])){
                require_once './includes/ExtraVals.php';
                $val = $_GET["login"];
                switch ($val){
                    case ExtraVals::DB_CONN_ERROR:
                        echo "<p class='error'>DB connection error</p>";
                        break;
                    
                    case ExtraVals::EMPTY_STRING:
                        echo "<p class='error'>Empty field</p>";
                        break;
                    
                    case ExtraVals::NOT_SUBMITTED;
                        echo "<p class='error'>Don't be a bitch, submit!</p>";
                        break;
                    
                    case ExtraVals::QUERY_ERROR;
                        echo "<p class='error'>DB error (query)</p>";
                        break;
                    
                    case ExtraVals::USER_DOES_NOT_EXISTS;
                        echo "<p class='error'>This user does not exists</p>";
                        break;
                    
                    default:
                        break;
                }
            }
            
            if (isset($_GET["logged_user"])){
                require_once './includes/ExtraVals.php';
                $val = $_GET["logged_user"];
                switch ($val) {
                    case ExtraVals::SESSION_PARAMS_NOT_FOUND:
                        echo "<p class='error'>Login params error</p>";
                        break;
                    
                    case ExtraVals::USER_LOGGED_OUT:
                        echo "<p class='error'>You logged out. Come back soon!</p>";
                        break;

                    default:
                        break;
                }
            }
        ?>
        
    </body>
</html>
