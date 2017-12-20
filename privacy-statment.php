<?php
session_start();
require_once "helperScripts.php";

$id = $_SESSION['id'];
$role = $_SESSION['role'];

//check if user has already been authenticated
if(!SessionIsValid()) header('Location: logIn');

if($_POST){
    if(isset($_POST['logout'])){
        endSession();
        header('Location: logIn');
    }
}

?>
<html>
    
    <head>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/home-style.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="JS/jquery-3.0.0.min.js" ></script>
        <script src="JS/removeBlog.js" ></script>
        
    </head>
<body>
    <div style="height:70px;"></div>
<?php require_once "navbar.php" ?>

<div class="container">
                <h1 class= 'block' style="text-align:center; color: #fff; margin-bottom:0;">Privacy Statment</h1>
        <div class= 'row' >
            
            <h2 class= 'block'>Privacy Statment</h2>
            <p>In this site, we take our users privacy series. We do not share our data with any third parties. We only collect data to provide the best experience for our users. We collect the bare minimum data to allow the site to work. We also openly share what data we collect from our users. We only collect our user's email, username, password (passwords are salted and hashed), blogs that our user's post, and also the day that the users posted the blog. You can delete your blog if you do not want to share it with the public. For any more question please contact me at robertoaguilar6@gmail.com  
            </p>
                        
            
            <p class='username'>By: admin</p>
        </div>
    </div><!-- /container -->

</body>
    <?php require_once 'footer.php'?>
</html>

