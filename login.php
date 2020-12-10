<?php
require_once "connect.php";

$username = "";
$password = "";
$user_error = "";
$pw_error = "";
$query = "";
$user_param = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $user_error = "Please enter your username.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $pw_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($user_error) && empty($pw_error)){
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, "s", $user_param);
            $user_param = $username;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashpw);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashpw)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION[username] = $username;

                            header("location:profile.php");
                        } else{
                            $pw_error = "Wrong Password!";
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
    </head>
    <body>
		<div class="maindiv">
			<form action="login.php" method="post"> 
                <h2>Log In</h2>
                <p> Once success, you will be redirected to profile page.</p>
                <p> Otherwise, please retry username or password.</p>
				<label>Username:</label>
                <input class="input" type="text" name="username">

				<label>Password:</label>      
                <input class="input" type="text" name="password">

                <input type="submit" value="Submit">	
</form>
</div>
</body>
</html>