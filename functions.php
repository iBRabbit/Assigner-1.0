<?php 

$connectionID = mysqli_connect("localhost","root","","seproject");

if ($connectionID -> connect_errno) {
    echo "Failed to connect to MySQL: " . $connectionID -> connect_error;
    exit();
} 

function StartLoginSession(){
    session_start();
    if(!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }
}


function GetUsernameByID($uid){
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE accountID = $uid");
    $row = mysqli_fetch_assoc($result);
    
    return (mysqli_num_rows($result) > 0) ? $row["username"] : NULL;
}

function GetStatusNameByID($statusID){
    if($statusID == 0) return "Not Complete";
    if($statusID == 1) return "25% Complete";
    if($statusID == 2) return "50% Complete";
    if($statusID == 3) return "75% Complete";
    if($statusID == 4) return "100% Complete";

    return NULL;
}

function GetUserData($username){
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE username = '$username'");
    $userdata = mysqli_fetch_assoc($result);
    return $userdata;
}

function ValidateRegister($input) {
    global $connectionID;
    $username = $input["input-username"];
    $password = mysqli_real_escape_string($connectionID,$input["input-password"]);

    $check = mysqli_query($connectionID, "SELECT username FROM accounts WHERE username = '$username'");
    if(mysqli_fetch_assoc($check)) {
        echo "<script>alert('Username sudah dipakai')</script>";
        return 0;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $str = "INSERT INTO accounts VALUES ('NULL', '$username','$password')";
    mysqli_query($connectionID,$str);
    return 1;
}

function query($query){
    global $connectionID;
    $result = mysqli_query($connectionID, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

?>