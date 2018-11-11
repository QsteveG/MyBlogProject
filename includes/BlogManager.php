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
//    echo "countPosts";
//    if($this->sqliInstance == NULL){
//        echo "Sqli instance == NULL";
//    }
    
    $query = "SELECT COUNT(ID) as counter FROM posts WHERE post_type='post'";
    
    if ($statement = $this->sqliInstance->prepare($query)){
        if(!$statement->execute()){
//            echo "error execute";
        }
        $res = $statement->get_result();
        return $res->fetch_assoc()["counter"];
    }else{
//        echo "Prepared error";
    }
}

function getPosts(){
     if ($this->sqliInstance == NULL){
        echo "instance == NULL";
        return false;
    }
    
    $query = "SELECT * FROM posts WHERE post_type='post' ORDER BY date_creation DESC";
    
    $res = FALSE;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        if (!$statement->execute()){
            echo "Execute error";
            $res = FALSE;
        }else{

            $result = $statement->get_result();
            
            $counter = $this->countPosts();
//            $res = $result;
            
            if ($counter <= 0){
                $statement->free_result();
                $statement->close();
                echo "Post counter: ".$counter;
                return FALSE;
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
                $tag .= "<form action=\"./deletePost.php\" method=\"POST\">";
                $tag .= "<button type=\"submit\" name=\"deletePostID\" value=\"".$row["ID"]."\">Delete</button>";
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
        echo "prepare error";
        $res = FALSE;
    }
    
    return $res;
}

function getPostTitleByID($postID){
    $query = "SELECT title FROM posts WHERE ID=?";
    $result = NULL;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $postID);
        if ($statement->execute()){
            $res = $statement->get_result();
            $result = $res->fetch_assoc()["title"];
        }else{
            $result = NULL;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $result = NULL;
    }
    
    return $result;
}

function getPostContentByID($postID){
    $query = "SELECT content FROM posts WHERE ID=?";
    $result = NULL;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $postID);
        if ($statement->execute()){
            $res = $statement->get_result();
            $result = $res->fetch_assoc()["content"];
        }else{
            $result = NULL;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $result = NULL;
    }
    
    return $result;
}

function getPostDate($postID){
    $query = "SELECT date_creation AS date FROM posts WHERE ID=?";
    $result = NULL;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $postID);
        if ($statement->execute()){
            $res = $statement->get_result();
            $result = $res->fetch_assoc()["date"];
        }else{
            $result = NULL;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $result = NULL;
    }
    
    return $result;
}

function editPost($newTitle, $newContent, $postID, $newDate){
    if (empty($newTitle) || empty($newContent)){
        return FALSE;
    }
    
    $result = FALSE;
    
    $query = "UPDATE posts SET title=?, content=?, date_creation=? WHERE ID=?";
//    $newDate2;
    if (empty($newDate)){
        //TO CHECK!!!!
        $newDate2 = (new DateTime(date("d-m-Y")))->format("U");
    }else{
        $newDate2 = (new DateTime($newDate))->format("U");
    }
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("ssii", $newTitle, $newContent, $newDate2, $postID);
        if ($statement->execute()){
            $result = TRUE;
        }else{
            $result = FALSE;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $result = FALSE;
    }
    
    return $result;
}

function deletePost($postID){
    $query = "DELETE FROM posts WHERE ID=?";
    $res = FALSE;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $postID);
        if ($statement->execute()){
            $res = TRUE;
        }else{
            $res = FALSE;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $res = FALSE;
    }
    
    return $res;
}

function checkUserAuthor($post_ID, $author){
    $query = "SELECT author FROM posts WHERE ID=?";
    $result = FALSE;
    
    if ($statement = $this->sqliInstance->prepare($query)){
        $statement->bind_param("i", $post_ID);
        if ($statement->execute()){
            $res = $statement->get_result();
            $auth = $res->fetch_assoc()["author"];
            $result = (strcmp($auth, $author) == 0);
        }else{
            $result = FALSE;
        }
        
        $statement->free_result();
        $statement->close();
    }else{
        $result = FALSE;
    }
    
    return $result;
}

function addNewPost($author, $typeContent, $title, $content){
    $query = "INSERT INTO posts (ID, author, post_type, date_creation, title, content) VALUES ('', ?, ?, ?, ?, ?)";
    $res = FALSE;
    
    if ($stmt = $this->sqliInstance->prepare($query)){
        $stmt->bind_param("ssiss", $author, $typeContent, time(), $title, $content);
        if($stmt->execute()){
            $res = TRUE;
        }else{
            $res = FALSE;;
        }
        
        $stmt->free_result();
        $stmt->close();
    }else{
        $res = FALSE;;
    }
    
    return $res;
}

function DBDisconnect(){
//    if ($this->DBManager != NULL){
        $this->DBManager->DBDisconn();
//    }
}
           
}
