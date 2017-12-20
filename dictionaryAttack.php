<?php
session_start();

require_once "helperScripts.php";
$id = $_SESSION['id'];
$role = $_SESSION['role'];

//check if user has already been authenticated
if(!SessionIsValid()) header('Location: logIn');

$header = "<h2 class= 'block'>Hash Reverser:</h2><br>
<form method='post' enctype='multipart/form-data'>         
            <select name='hashalgoritms' size='1'>
	           <option value='md2'>md2</option>
	           <option value='md4'>md4</option>
                <option value='md5'>md5</option>
	           <option value='sha1'>sha1</option>
                <option value='sha256'>sha256</option>
	           <option value='sha384'>sha384</option>
                <option value='sha512'>sha512</option>
	           <option value='ripemd128'>ripemd128</option>
                <option value='ripemd160'>ripemd160</option>
                <option value='ripemd256'>ripemd256</option>
                <option value='ripemd320'>ripemd320</option>
            </select>
            <input type='text' name='userInput' placeholder='Please put what you wish to hash'><br>
            <input class='btn' type='submit'>
            </form>";
if($_POST){
    if(isset($_POST['userInput'])){
        $algorithm = $_POST['hashalgoritms']; 
        $HashedPasswords = gethash($algorithm);
        $input = $_POST['userInput'];
        if(isset($HashedPasswords[$input])) $p = $HashedPasswords[$input];
        else $p = 'could not find a match in our dictionary';
        $header = "<h2 class= 'block'>$algorithm: $p</h2><br>";
    }
    else if(isset($_POST['logout'])){
        endSession();
        header('Location: logIn');
    }
}


function gethash($alg){ 
    $st = file_get_contents('easypasswords/passworddictinary.txt', true);
    $hashinput = array();
    $file = preg_split ('/$\R?^/m', $st); 
    for($i=0; $i<count($file); $i++){
        $pass = hash($alg, $file[$i]);
        $hashinput[$pass] = $file[$i];
    }
    return $hashinput;
}
?>


<html>
    
    <head>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/home-style.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        
    </head>
<body>
    <div style="height:70px;"></div>
    <?php require_once "navbar.php" ?>

<div class="container">
                <h1 class= 'block' style="text-align:center; color: #fff; margin-bottom:0;">Hash Reverser</h1>
        <div class= 'row' >
            <?php echo $header?>
            
        </div>
    </div><!-- /container -->

</body>
    <?php require_once 'footer.php'?>
</html>

