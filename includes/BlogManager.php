<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './includes/DBManager.php';

class BlogManager{
    
private $DBManager;
private $sqliInstance;

function __construct() {
    $this->DBManager = new DBManager();
    $this->sqliInstance = $this->DBManager->DBConn();
}

private function countPosts(){
    $query = "SELECT COUNT (ID) as counter FROM posts WHERE post_type='post'";
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->execute();
        $res = $statement->get_result();
        return $res->fetch_assoc()["counter"];
    }
}

function getPosts(){
//     if ($this->sqliInstance == null){
//        return false;
//    }
    
    $query = "SELECT ID, author, post_type, date_creation, title, content FROM posts
                WHERE post_type='post' 
                ORDER BY date_creation DESC";
    
    $res = FALSE;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        if (!$statement->execute()){
//            echo "Execute error";
            $res = FALSE;
        }else{

            $result = $statement->get_result();
            
            $res = $result;
            
            $counter =$this->countPosts();
            
            if ($counter < 0){
                return true;
            }
            
            $tag = '<ul style="list-style-type: none">';
            
            //ADD BUTTON!!
            while ($row = $result->fetch_assoc()){
                $date = $row["date_creation"];
                $tag .= '<li>';
                $tag .= "<h2 class=\"title\">".$row["title"]."</h2>";
                $tag .= "<h4 class=\"author\">".$row["author"]." (".date("d-m-Y", $date).")</h4>";
                $tag .= "<p class=\"content\">".$row["content"]."</p>";
                $tag .= "<form action=\"./editPost.php\" method=\"POST\">";
                $tag .= "<button type=\"submit\" name=\"editPostID\" value=\"".$row["ID"]."\">Edit</button>";
                $tag .= "</form>";
                $tag .= '</li>';
                $tag .= '<br>';
            }
            
            $tag .= '</ul>';
            
            echo $tag;
            
            $res = TRUE;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
//        echo "prepare error";
        $res = FALSE;
    }
    
    return $res;
}

function getPostTitleByID($postID){
        $query = "SELECT title FROM posts WHERE ID=?";

        if ($statement = $this->sqliInstance->prepare($query)){
            $statement->bind_param("i", $postID);
            if ($statement->execute()){
                $res = $statement->get_result();
                return $res->fetch_assoc()["title"];
            }else{
                return NULL;
            }
        }else{
            return NULL;
        }
}

function getPostContentByID($postID){
        $query = "SELECT content FROM posts WHERE ID=?";

        if ($statement = $this->sqliInstance->prepare($query)){
            $statement->bind_param("i", $postID);
            if ($statement->execute()){
                $res = $statement->get_result();
                return $res->fetch_assoc()["content"];
            }else{
                return NULL;
            }
        }else{
            return NULL;
        }
}

function getPostDate($postID){
    $query = "SELECT date_creation AS date FROM posts WHERE ID=?";

        if ($statement = $this->sqliInstance->prepare($query)){
            $statement->bind_param("i", $postID);
            if ($statement->execute()){
                $res = $statement->get_result();
                return $res->fetch_assoc()["date"];
            }else{
                return NULL;
            }
        }else{
            return NULL;
        }
}

function editPost($newTitle, $newContent, $postID, $newDate){
    if (empty($newTitle) || empty($newContent)){
        return FALSE;
    }
    $query = "UPDATE posts SET title=?, content=?, date_creation=? WHERE ID=?";
    $newDate2;
    if (empty($newDate)){
        //TO CHECK!!!!
        $newDate2 = (new DateTime(date("d-m-Y")))->format("U");
    }else{
        $newDate2 = (new DateTime($newDate))->format("U");
    }
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("ssii", $newTitle, $newContent, $newDate2, $postID);
        if ($statement->execute()){
            return TRUE;
        }else{
            return FALSE;
        }
    }else{
        return FALSE;
    }
}

function checkUserAuthor($post_ID, $author){
    $query = "SELECT author FROM posts WHERE ID=?";
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $post_ID);
        if ($statement->execute()){
            $res = $statement->get_result();
            $auth = $res->fetch_assoc()["author"];
            if (strcmp($auth, $author) == 0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
        
    }else{
        return FALSE;
    }
}

function addNewPost($author, $typeContent, $title, $content){
//    if ($this->sqliInstance == null){
//        return false;
//    }
    $query = "INSERT INTO posts (ID, author, post_type, date_creation, title, content) VALUES ('', ?, ?, ?, ?, ?)";
    $res = FALSE;
    if ($stmt = $this->sqliInstance->prepare($query)){
        $stmt->bind_param("ssiss", $author, $typeContent, time(), $title, $content);
        if($stmt->execute()){
            $res = TRUE;
        }else{
//            echo "Execute error";
            $res = FALSE;;
        }
    }else{
//        echo "prepare error";
        $res = FALSE;;
    }
    
    $stmt->free_result();
    $stmt->close();
    
    return $res;
}

function DBDisconnect(){
//    if ($this->DBManager != NULL){
        $this->DBManager->DBDisconn();
//    }
}
           
}
