<?php

$link = mysqli_connect('localhost', 'root', 'egg123', 'kw2263');

if($link === false){
    die("ERROR: Connection Failed!" . mysqli_connect_error());
}
?>