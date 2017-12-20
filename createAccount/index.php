<?php
session_start();

require_once "../loginInfo.php";
require_once "../helperScripts.php";

$nameNotAvailable = "";
$emailtaken = "";
$botDetection = "";

if($_POST){
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    
    if(isset($_POST['username']))
        $username = removeHTML($_POST['username']);
    
    if(isset($_POST['email']))
        $email = removeHTML($_POST['email']);
    
    if(isset($_POST['password']))
        $pass = removeHTML($_POST['password']);
    
        $fail = validate_userName($username);
        $fail .=  validate_email($email);
        $fail .= validate_password($pass);
        $fail .= isNotABot($secret);
    
    
    if($fail == ""){
        $username = removeHTML($username);
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die($conn->connect_error);
        
        if(!userNameAvailable($conn, $username)){ 
            $nameNotAvailable= '<p class = "error">username is taken</p><br>';
       }
    
        elseif(!emailIsAvailable($conn, $email)) {
            $emailtaken = '<p class = "error">email is already being used</p>';
        }
        else{
            $salt = randomString(10);
            $auth = randomString(20);
            $hashedPass = hash('sha256', $pass . $salt);
            
            $stmt = $conn->prepare("INSERT INTO users(email, username, password, role, salt, authtoken) values(?, ?, ?, 'standard', ?, ?)");
            $stmt->bind_param('sssss', $email, $username, $hashedPass, $salt, $auth );
	        $result = $stmt->execute();
        
            if (!$result){
                echo 'server error';
                die($conn->error);
            } 
            $stmt->close();
            $id = getUserID($conn, $username);
            if($id){
                startSession($auth, $id, 'standard',$username);
                header('Location: ../');
            }
            else echo '<br> could not create user';
            
        }
        $conn->close();
    }
    else $botDetection = "<p class = 'error'>$fail</p>";
    
}


//@Desc: makes sure that user was created and then returns the users ID
function getUserID($conn, $username){
    $stmt = $conn->prepare("select id from users where username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $stmt->close();
    if(!$row[0]) return false;
    return $row[0];
} 

//@Desc: Checks if username is still availble 
function userNameAvailable($conn, $username ){
    $stmt = $conn->prepare("select * from users where username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $stmt->close();
    if(!$row) return true;
    return false;
} 

//@Desc: checks if email has already been used
function emailIsAvailable($conn, $email ){
    $stmt = $conn->prepare("select * from users where email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $stmt->close();
    if(!$row)return true;
    return false;
} 

echo <<<_END

<html>
    
    <head>
        <link rel="stylesheet" href="../CSS/style.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="../JS/authenticate.js"></script>
    </head>
<body>
<div class="container">
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="post" action="index.php" onsubmit="return CreateAccountValidate(this)">
                <span id="reauth-email" class="reauth-email"></span>
                $botDetection
                $nameNotAvailable
                $emailtaken
                <input type="text" id="inputUserName" class="form-control" placeholder=" What should we call you" name = "username" required autofocus>
                <input type="email" id="inputEmail" class="form-control" placeholder=" Email address"  name = "email" required>
                <input type="password" id="inputPassword" class="form-control" placeholder=" Password" name = "password" required>
                <div id = "captcha" class="g-recaptcha" data-sitekey="6LfkJjcUAAAAAEkOPyXqrjSQqL974aBa2nrBMAZS"></div>
                <button id="submit" class="btn btn-lg btn-primary btn-block btn-register" type="submit" >Sign Up</button>
            </form><!-- /form -->
            

            <a href="../logIn" class="forgot-password">
                I already have an account
            </a>

        </div><!-- /card-container -->
    </div><!-- /container -->

</body>
</html>

_END;


?>
