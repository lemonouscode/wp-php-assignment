<?php 
require_once('connectToApiClass.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $connectToApi = new connectToApiClass('https://symfony-skeleton.q-tests.com/api/',$email, $pass);

    $res = $connectToApi->getSymfonyToken('v2/token');

    // Converting stdClass to Array
    $array = json_decode(json_encode($res), true);
  
    // IF there is error stop script and show message
    if(property_exists($res, 'exception')){
        die("Wrong Email Or Password");
    }

    // Getting Token from array
    $token = $array['token_key'];


    // Request passed store token into Session
    if(!isset($_COOKIE["symfony_token"])) {
        $cookie_duration = 6 * 60 * 60; // 6 hours in seconds
        setcookie('symfony_token', $token, time() + $cookie_duration, '/');
    }
    
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>  
        form{
            display: flex;
            flex-direction: column;
            gap:20px;
            max-width: 600px;
        } 
    </style>  
</head>
<body>
    
    <form action="" method="POST">
        <label for="">Email:</label>
        <input type="email" name="email">
        <label for="Password">Password:</label>
        <input type="text" name="password">

        <input type="submit" value="Login">

    </form>

</body>
</html>