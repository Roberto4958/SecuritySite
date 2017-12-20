<?php
session_start();
require_once "loginInfo.php";
require_once "helperScripts.php";



class blog
{
        
}

$myObj = new blog;

//validate that request came from ajax request
if(!$_POST || !isset($_POST['id']) || !isset($_POST['userid'])) $myObj->success = false;

else{
    if(SessionIsValid() && validateUserOwnershipOfBlog($hn, $un, $pw, $db)){
        
        if(deleteBlog($hn, $un, $pw, $db)) $myObj->success = true;
        
        else $myObj->success = false;
    }
    else $myObj->success = false;
}

$myJSON = json_encode($myObj);

echo $myJSON;

function deleteBlog($hn, $un, $pw, $db){

    $success = true;
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) return false;
    
    $blogid = $_POST['id'];
    
    $sql = " delete from blogs where id = $blogid";
    $conn->query($sql);        
    
    $conn->close();
    return $success;
}

function validateUserOwnershipOfBlog($hn, $un, $pw, $db){
    
    if($_SESSION['role'] == 'admin') return true;
    if($_POST['userid'] != $_SESSION['id']) return false;

    $succes = true;
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) return false;
    
    $id = $_SESSION['id'];
    $auth = $_COOKIE['token'];
    $blogid = $_POST['id'];
    
    $sql = "select blogs.body from users, blogs where users.id = blogs.userID and users.id=$id and users.authtoken = '$auth' and blogs.id=$blogid";
    
    $result = $conn->query($sql);        
    $result->data_seek(0);
    $obj = $result->fetch_array(MYSQLI_ASSOC);
    
    if(!$obj['body'])$succes = false;
    $result->close();
    $conn->close();
    return $succes;
}

?>
