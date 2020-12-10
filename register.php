<?php
require_once "connect.php";

$username = "";
$password = "";
$re_password = "";
$user_error = "";
$pw_error = "";
$repw_error = "";
$query = "";
$stmt = "";
$user_param = "";
$pw_param = "";

if($_SERVER[REQUEST_METHOD] == "POST"){
    if(empty(trim($_POST["username"]))){
        $user_error = "Username cannot be blank.";
    } else{
        $query = "SELECT id FROM users WHERE username = ?";
    }

    if($stmt = mysqli_prepare($link, $query)){
        mysqli_stmt_bind_param($stmt, "s", $param);
        $user_param = trim($_POST["username"]);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                $user_error = "This username already exists, please enter another:)";
            } else{
                $username = $user_param;
            }
        }
        mysqli_stmt_close($stmt);
    }

    if(empty(trim($_POST["password"]))){
        $pw_error = "Password cannot be blank.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["re_password"]))){
        $repw_error = "Retype in your password.";
    } else{
        $re_password = trim($_POST["re_password"]);
        if(empty($pw_error) && $password != $re_password){
            $repw_error = "Passwords entered do not match.";
        }
    }

    if(empty($user_error) && empty($pw_error) && empty($repw_error)){
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, "ss", $user_param, $pw_param);
            $user_param = $username;
            $pw_param = password_hash($password, PASSWORD_DEFAULT);

            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
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
        <title>Register</title>
    </head>
    <body>
		<div class="maindiv">
			<form action="register.php" method="post"> 
                <h2>Sign Up Here!</h2>
                <p> Once success, you will be redirected to log in page.</p>
                <p> Otherwise, please try another username.</p>
				<label>Username:</label>
                <input class="input" type="text" name="username">

				<label>Password:</label>      
                <input class="input" type="text" name="password">

				<label>Confirm Password:</label>
                <input class="input" type="text" name="re_password">

                <input type="submit" value="Submit">	
</form>
</div>
</body>
</html>

