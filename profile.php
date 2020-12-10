<?php
require_once "connect.php";

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
  header("location: login.php");
  exit;
}

if(isset($_POST["but_upload"])){
  $username = $_SESSION["username"];
  $name = $_FILES["file"]["name"];
  $target_dir = "upload/";
  $target_file = $target_dir . basename($_FILES["file"]["name"]);

  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  $extensions_arr = array("jpg", "jpeg", "png", "gif");

  if( in_array($imageFileType, $extensions_arr) ){

     move_uploaded_file($_FILES['file']['tmp_name'], $target_dir.$name);

     $query = "INSERT INTO images (username, img_name, img_dir) VALUES ('$username', '$name', '$target_dir$name')";
     mysqli_query($link, $query);
  } 
}
?>

<!DOCTYPE html>
<html>
    <head>
      <title>Profile</title>
    </head>
    <body>
      <a href="logout.php">Log Out</a>
		<div class="maindiv">
      <form method="post" action="profile.php" enctype='multipart/form-data'>
        <input type='file' name='file'>
        <input type='submit' value='Upload' name='but_upload'>
      </form>
    </div>
    <h1 style="text-align: center">Welcome! 
      <b>
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
      </b>.
    </h1>
    <p style="text-align: center">Below are your stored images.</p>
    <?php
    $username = $_SESSION["username"];
    $query = "SELECT * FROM images WHERE username = '$username'";
    $result = mysqli_query($link, $query);
    while($data = $result->fetch_assoc()){
      //print_r($data);
      echo "<h2>{$data['img_name']}</h2>";
      echo "<img src='{$data['img_dir']}' width='40%' height='40%'>";
    }
    ?>
    </body>
</html>

  