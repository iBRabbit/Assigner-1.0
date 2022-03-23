<?php
    require_once "functions.php";
    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $groupid = $_POST["input-groupid"];
?>