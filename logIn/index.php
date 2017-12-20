<?php
session_start();

require_once "../loginInfo.php";
require_once "../helperScripts.php";

$emailDoesnotexist = '';
$wrongpassword = "";

if($_POST){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    if(stringisSafe($email) && stringisSafe($pass)){
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die($conn->connect_error);
        
        $salt  = getSalt($conn, $email);
        if(! $salt){
            $emailDoesnotexist = '<p class = "error">Invalid email/password combination</p>';
        }
        else{
            $hashedpass = hash('sha256', $pass . $salt);
            $loginInfo = passIsCorrect($conn, $email, $hashedpass);
        
            if(!$loginInfo){
                $wrongpassword = '<p class = "error">Invalid email/password combination</p>';
            }
            else{
                $token = randomString(20);
                $id= $loginInfo[0];
                $conn->query("update users set authtoken = '$token' where id = $id");
                
                //$loginInfo[0] = id $loginInfo[1] = role, $loginInfo[2]=  username 
                startSession($token, $loginInfo[0], $loginInfo[1], $loginInfo[2]);
                header('Location: ../index.php');
            }
        }
        
        $conn->close();
    }
    //time()
}


function getSalt($conn, $email){
    $stmt = $conn->prepare("select salt from users where email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $stmt->close();
    if(!$row) return false;
    return $row[0];
}

function passIsCorrect($conn, $email, $hashedpass){
    $stmt = $conn->prepare("select id, role, username from users where email = ? and password = ?");
    $stmt->bind_param('ss', $email, $hashedpass);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $stmt->close();
    if(!$row) return false;
    return array($row[0], $row[1], $row[2]);
}

echo <<<_END
<html>
    
    <head>
        <link rel="stylesheet" href="../CSS/style.css">
    </head>
<body>
<div class="container">
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="post" action="index.php">
                <span id="email" class="reauth-email"></span>
                $emailDoesnotexist
                $wrongpassword
                <input type="email" id="inputEmail" class="form-control" placeholder=" Email address" name = 'email' required autofocus>
                
                <input id="inputPassword" type="password" id="inputPassword" class="form-control" placeholder=" Password" name = 'password' required>
                <div id="remember" class="checkbox">
                
<!--
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
-->
                </div>
                <button id="btn-signin" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
                
            </form><!-- /form -->
            <button class="btn btn-lg btn-primary btn-block btn-register" onclick="location.href='../createAccount';" >Create Account</button>

        </div><!-- /card-container -->
    </div><!-- /container -->

</body>
</html>

_END;

?>