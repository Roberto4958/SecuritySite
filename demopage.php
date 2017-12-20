<?php
session_start();

//Does adding salt to the end adds the same amount of security than adding it in front and back
//what should I do the email I have? Should I encrypt them? 
require_once "loginInfo.php";
require_once "helperScripts.php";

$id = $_SESSION['id'];
$role = $_SESSION['role'];

//check if user has already been authenticated
if(!SessionIsValid()) header('Location: logIn');


//determin if user logged out 
if($_POST){
    if(isset($_POST['logout'])){
        endSession();
        header('Location: logIn');
    }
    elseif(isset($_POST['blog'])){
        
        $title = $_POST['header'];
        $content = $_POST['content'];
        
        if(stringisSafe($title) && stringisSafe($content)){
            
            $username = $_SESSION['username'];
            $userId = $_SESSION['id'];
            addBlog($userId, $username,$title, $content, $hn, $un, $pw, $db);
       }
    }
    
}
$bd = getBlogs($hn, $un, $pw, $db);

function getBlogs($hn, $un, $pw, $db){
    $notSuccessful = '  <div class= "row">
                            <h2 class="error">Error: Could not access the database</h2><br> 
                        </div>';
    
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) return $notSuccessful;
    
    $sql = "select * from demoblogs order by id desc";
    $result = $conn->query($sql);
    
    $rows = $result->num_rows;    
    $str = '';
    for($i=0; $i < $rows; $i++){
        
        $result->data_seek($i);
        $obj = $result->fetch_array(MYSQLI_ASSOC);
        $str .= generateBlog($obj['ID'], $obj['userID'], $obj['username'], $obj['title'], $obj['body'], $obj['date'], null); 
    }
    
    
    $result->close();
    $conn->close();
    return $str;
}

//@Desc: generates a blog given all the parmeters
function generateBlog($id, $userid, $username, $title, $content, $date, $img){
    $currentID = $_SESSION['id'];
    $delete = '';
    $role = $_SESSION['role'];
    if ($currentID == $userid || $role == 'admin') $delete = "<i data-id='$id' data-userId='$userid' class='fa fa-times delete' aria-hidden='true'></i>";
    //return "<div data-department=''=\""+department+"\"class= 'row'>
    
    return "<div class= 'row' >
                <h2 class= 'block'>$title</h2><br> 
                <p class= 'block'>$content</p>
                <p class='username'>By: $username</p>
                <p class = 'date'>$date</p>
                $delete
        </div>";
}

//@Desc: Adds blog to the database
function addBlog($userId, $username, $title, $content, $hn, $un, $pw, $db){
    
    $success = true;
    $time = date('m/d/y', time());
    
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) return false;
    
    $sql = "insert into demoblogs(userID, username, title,body, date) values($userId, '$username', '$title', '$content', '$time' )";
    echo $sql;
    if(!$conn->query($sql)) $success = false;
    
    $conn->close();
    return $success;
}
?>

<html>
    
    <head>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/home-style.css">
        <link rel="stylesheet" href="CSS/demo-style.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="JS/jquery-3.0.0.min.js" ></script>
        <script src="JS/demoremoveBlog.js" ></script>
        
    </head>
<body>
    <div style="height:70px;"></div>
<?php require_once "navbar.php" ?>

<div class="container">
    <div class="row">
        <form method= "post">
            Do you have any insite in cyber attacks? (Please note that this page is unsecure and ment to demo attacks)<br><br>
            <input id= "blog" placeholder="Header" name="header"><br>
            <textarea #id= "blog" cols="100" rows="5" name="content" placeholder="Please Share With Us"></textarea><br><br>
            <button  class="btn" type="submit" name="blog">Let Us Know</button>
        </form>
    </div> 
    <?php 

    echo $bd; ?>
    </div><!-- /container -->

</body>
    <?php require_once 'footer.php'?>
</html>

