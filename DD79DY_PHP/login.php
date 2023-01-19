<?php
session_start();
$error = [];
function regex_password($pword){
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
    return preg_match('/[a-z]/',$pword) &&
           preg_match('/[A-Z]/',$pword) &&
           preg_match('/[0-9]/',$pword) &&
           preg_match($pattern,$pword);
}

// Registration page
if(isset($_POST['register'])) {
    if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password_confirm'])) {
        $error = "All fields are required";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else if ($_POST['password'] !== $_POST['password_confirm']) {
        $error = "Passwords do not match";
    } else if($_POST['password'] < 8){
        $error = "Password is too short";
    } else if(!regex_password($_POST['password'])){
        $error = "Password must contain one small one capital one number and one special character";
    }else
    {
        if(file_exists('users.json')){
            $users = json_decode(file_get_contents('users.json'), true);
        }else{
            $users = array();
        }
        $emailoruserExists = false;
        foreach($users as $user) {
            if($user['email'] == $_POST['email'] || $user['username'] == $_POST['username']) {
                $emailoruserExists = true;
                break;
            }
        }
        if($emailoruserExists) {
            $error = "Email or Username already exists";
        } else {
            $users[] = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ];
            file_put_contents('users.json', json_encode($users));
            $error = "Registration successful, you can now login";
        }
    }
}

// Login page
if(isset($_POST['login'])) {
    if(empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username and password are required";
    } else {
        if(file_exists('users.json')){
            $users = json_decode(file_get_contents('users.json'), true);
        }else{
            $users = array();
        }
        $usernameExists = false;
        $passwordMatch = false;
        foreach($users as $user) {
            if($user['username'] == $_POST['username']) {
                $usernameExists = true;
                if(password_verify($_POST['password'], $user['password'])) {
                    $passwordMatch = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true;
                    header("Location: index.php");
                }
                break;
            }
        }
        if(!$usernameExists) {
            $error = "Username not found";
        } else if(!$passwordMatch) {
            $error = "Incorrect password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body >
<div>
    <?php if (!empty($error)): ?>
        <div class="error"><?= $error?></div>
    <?php endif; ?>
    <?php if (!empty($success)): 
    header('Location: index.php');
    exit;
    ?>
        <div class="success"><?= $success ?></div>         
    <?php endif; ?>
</div>

<form action="" method="post">
    <label for="username">Username:</label>
    <input type="username" name="username" id="username">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="submit" name="login" value="Login">
</form>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username">
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
    <br>
    <label for="password_confirm">Confirm Password:</label>
    <input type="password" name="password_confirm" id="password_confirm">
    <br>
    <input type="submit" name="register" value="Register">
</form>

</body>
</html>
 