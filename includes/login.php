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
        
//        if (strlen($username) > 0 && strlen($password) > 0){

            $query = "SELECT COUNT(ID) AS counter FROM users WHERE username=? AND password=?";
//            $query = "SELECT COUNT(ID) AS counter FROM users";
            if ($stmt = $mysqliInstance->prepare($query)){
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();
//                echo "Num rows: ".($result->fetch_assoc()["counter"]);
                
                if ($result->fetch_assoc()["counter"] == 1){
                    session_start();
                    $_SESSION[ExtraVals::IS_LOGGED_IN] = true;
                    $_SESSION[ExtraVals::USER_LOGGED] = strip_tags($username);
                    header("Location: ../signedin.php?login=".ExtraVals::LOGIN_SUCCESS);
                }else{
                    header("Location: ../index.php?login=".ExtraVals::USER_DOES_NOT_EXISTS);
                }
                
                //RETURN RESULT FROM QUERY
//                if ($row = $result->fetch_assoc()){
//                    echo "User exits<br><br>";
////                    LOGIN SUCCESS
////                    header("Location: ../signedin.php?login=".ExtraVals::LOGIN_SUCCESS);
//                        
//                }else{
//                    header("Location: ../index.php?login=".ExtraVals::USER_DOES_NOT_EXISTS);
//                    echo "User DOES NOT exists<br><br>";
//                }
                
                   //GET ROWS
//                while ($row = $result->fetch_assoc()) {
//                    echo $row["ID"]."<br><br>";
//                }
                                
                
                //ITERATE THROUGHT ELEMENT IN ARRAY
//                echo "<br><br><br><br>";
//                
//                $i = 0;
//                while ($row = $result->fetch_row()){
////                    echo $row[$i]."<br><br>";
//                    for ($i = 0; $i<count($row); $i++){
//                        echo $row[$i]."<br>";
//                    }
//                    
//                    $i = 0;
//                    echo "<br><br>";
//                }
                
                $stmt->free_result();
                $stmt->close();

            }else{
                echo "Prepare error";
                header("Location: ../index.php?login=".ExtraVals::QUERY_ERROR);
            }
            
            $DBManager->DBDisconn();
            
//        }else{
//            header("Location: ../index.php?login=".ExtraVals::LENGTH_ZERO);
//        }
    }
        
}else{
    echo "submit not set";
    header("Location: ../index.php?login=".ExtraVals::NOT_SUBMITTED);
}

?>
