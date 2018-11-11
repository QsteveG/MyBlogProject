<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './ExtraVals.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["submit"])){
    require_once './DBManager.php';
    $DBManager = new DBManager();
    $mysqliInstance = $DBManager->DBConn();
    
    if ($mysqliInstance == null){
        echo "sqli conn NULL";
        header("Location: ../index.php?login=".ExtraVals::DB_CONN_ERROR);
        exit();
    }
    
    if (empty($_POST["username"]) || empty($_POST["password"])){
        header("Location: ../index.php?login=".ExtraVals::EMPTY_STRING);
        $DBManager->DBDisconn();
        exit();
    }else{
        $username = mysqli_real_escape_string($mysqliInstance, $_POST["username"]);
        $password = mysqli_real_escape_string($mysqliInstance, $_POST["password"]);
        
        echo "Username: ".$username."<br><br>Password: ".$password."<br><br>";
        

            $query = "SELECT COUNT(ID) AS counter FROM users WHERE username=? AND password=?";
            if ($stmt = $mysqliInstance->prepare($query)){
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->fetch_assoc()["counter"] == 1){
                    session_start();
                    $_SESSION[ExtraVals::IS_LOGGED_IN] = true;
                    $_SESSION[ExtraVals::USER_LOGGED] = strip_tags($username);
                    header("Location: ../signedin.php?login=".ExtraVals::LOGIN_SUCCESS);
                }else{
                    header("Location: ../index.php?login=".ExtraVals::USER_DOES_NOT_EXISTS);
                }
                
                $stmt->free_result();
                $stmt->close();

            }else{
                echo "Prepare error";
                header("Location: ../index.php?login=".ExtraVals::QUERY_ERROR);
            }
            
            $DBManager->DBDisconn();
    }
        
}else{
    echo "submit not set";
    header("Location: ../index.php?login=".ExtraVals::NOT_SUBMITTED);
}

?>
