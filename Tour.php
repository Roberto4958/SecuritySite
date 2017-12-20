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
                <h1 class= 'block' style="text-align:center; color: #fff; margin-bottom:0;">Website Tour</h1>
        <div class= 'row' >
            
            <p>Hi, welcome to my web application! This page will give you a tour of my web application. I will talk about my pages and give some insight on how I developed the page to avoid security risk. This app was created using HTML, CSS, JS/jQuery, and PHP.
            </p>
            
<!--        Create account blog-->
            <h2 class= 'block'>Create Account Page:</h2>
            <p>This page is where the user can create an account. This is required to access the home page. There a couple of security risk that we should consider in this page; the first being is a denial of service attack. I avoid this by adding a Google’s ReCaptcha. This captcha checks if the user is a robot or human by looking at the mouse movement. At submission, I make an API call to Google’s API to authenticate the user as a human.  
            </p>
            <p>I understand that the password is very sensitive information since a user can be using the same password on multiple sites. This is why I add a random 10-character string to the password (to avoid a dictionary attack) and hash (SHA-256) the password then store it in the database. In this case, if my database is compromised the attacker would not have access to my user's passwords. Unless the attacker is from the NSA and they left a back door open for the SHA algorithm.  
            </p>
            <p>In addition, this page needs to be protected from Cross-Site Scripting because when a user posts a blog it display’s their username shows on the blog. So I remove all HTML entities from the username before I store the username in the database.  
            </p>
            <p>
            This page also needs to be protected from SQL injection. This attack is prevented by inserting the user input as parameters instead of adding it into to String that contains the SQL.
            </p><br><br>
            
<!--            log in blog-->
            <h2  class= 'block'>Log In Page</h2>
            <p>This page is where the user can log in to the site. A user must always login to the page before accessing the site. They would be able to access the site for 24 hours without having log back in but after the 24 hours, they are logged out. </p><br><br>
            
<!--             session blog-->
            <h2  class= 'block'>Session Handling</h2>
             <p><img src="img/session-handling.png" style= "width:578.25px ; height:276px; float: left; " alt="session handling code">
           When the user logs in or creates an account I start a session. I remember the user using the Session and cookies. In the cookie, I store an authentication token (random String with 20 characters also stored in the database). In the session, I store the username, user role, users id, and a security string. This string is a hash of the user's authentication token, IP address, and the user's browser and OS machine. So every time I validate the user as logged in, I regenerate the hash and check if it matches the same hash that was generated when the user first logged in. This way if an attacker were to try to steal the session, the attacker would need to do multiple things. The attacker would need to steal the session and the cookie. The attacker would have to spoof his IP address to match his target and use the same browser and machine as his target. Hence it would be almost impossible to steal one of my user's sessions.</p><br><br>
            
            <h2>Home Page</h2>
            <p>The homepage is geared toward sharing information about security. The user can share with us any insight he has on security-related issues. They can post their blog and they can also delete their blog. The user can only delete their blog. However, the admin can delete any blog that they want. When the user signs in all of his blogs have an x on the upper right side of the blog. If the user wants to delete his blog, he would need to click the x and his blog. This page brings up many security questions.</p>
            
            <br><p><b>Is the page safe from SQL injection?</b>  Yes I input the user input as parameter to prevent SQL injection .</p>
            
            <br/><p><b>Is the page safe from Cross-Site Scripting?</b> Yes, I sanitize my input. </p>
            
            <br/><p ><b>Can an attacker delete any blog they want?</b> 
                I use jQuery to achieve the delete feature so I do consider the possibility of the user modifying the JavaScript. I prevent this attack by authenticating the user again and taking the user id from the session and comparing it with the userID in the blog. I also check the user's authentication token and compare it to the authentication token on the database. </p>
            
            <h2>Demo Page</h2>
            <p>This page is a demo page to simulate SQL injection and Cross-Site Scripting attack. I do not sanitize my input or enter the user inputs as parameters. So this page is vulnerable to SQL injection attack and Cross-Site Scripting Attack. I will demonstrate these attack. </p><br>
            
            <p>In this first example I input sql code into the input section</p>
            <div id= "sql_injection_1"></div><br>
            <p>
            <br>Once I submite this to the database it would run the following code on the database: <br>insert into blogs(userID, username, title,body, date) values(1, 'user1', 'SQL Injection Attack', 'I changed the date', '12/06/03';#, '12/07/17' ;)<br>Thus it would read it as correct SQL and insert the attackers date value instead of my value. When it loads the blog from the database it would show the date that the attaker set<br> </p>
            <div id= "sql_injection_2"></div>
            
            
            <p><br><br> Another attack that possible is Cross-Site Scripting. Hear is an example: <br></p>
            <div id= "cross_site_1"></div>
            <p><br> I inserted the JavaScript code in the input text box. The code I injected is not malicous. Now when someone visites the page the Javascript code would be retrived from the database and the HTML would read it as JavaScript code and run it.<br></p>
            <div id= "cross_site_2"></div>
            
            <h2><br>Hash Generator</h2>
            <p>Hashing is a very important concept in security. Hash is important because they are a one-way function. This means if you have a hashed string there is no way to get the old string back to plain text. This page simulates that. You can input any string in the text field and it would give you an option of what hash algorithm you want to use and it would generate the hash for you.</p>
            
            <h2>Hash Reverter</h2>
            <p>Since hashes are one-way function it is common practice to hash passwords before storing them in the database. To avoid having the exposing the passwords in plain text in the case of the site being compromised. But there is something called a dictionary attack. The way it works is that there is a file with many common passwords and their associated hash. So an attacker could look at a password that is hashed and look at the dictionary and if the dictionary has the hash the attacker would have the password in plain text. This page simulates the dictionary attack. I have a file with many common passwords; if the hash is a weak password this page would give you the plain text. </p>
            
            <h2 class= 'block'>Deployement</h2><br> 
            <p  class= 'block'>
            I deployed my web app on AWS using Elastic Beanstalk. AWS implements security groups which acts like a virtual firewall by controlling the traffic. When you launch an instance it has a security rule associated with it. When a new security group is created all inbound traffic is denied and outbound traffic is accepted by default. For my EC2 reference, I allowed HTTP traffic. For my RDS I only allowed traffic coming from my EC2 instance. I also allowed all traffic with my IP address. I added this rule to be able to access the database remotely. Bellow are the security groups for my EC2 instance and my RDS.
            </p>
              <p><br><br> <b>EC2 Rules: </b></p>
            <div id= "EC2-security-group-1"></div>
            <p><br><br> <b>RDS Rules: </b></p>
            <div id= "RDS-security-group-1"></div>
                <p class='username'>By: admin</p>
        </div>
    </div><!-- /container -->

</body>
    <?php require_once 'footer.php'?>
</html>

