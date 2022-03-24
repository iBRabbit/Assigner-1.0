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
    if($statusID == 0) return 0;
    if($statusID == 1) return 25;
    if($statusID == 2) return 50;
    if($statusID == 3) return 75;
    if($statusID == 4) return 100;

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
    $firstname = mysqli_real_escape_string($connectionID,$input["input-firstname"]);
    $lastname = mysqli_real_escape_string($connectionID,$input["input-lastname"]);

    $check = mysqli_query($connectionID, "SELECT username FROM accounts WHERE username = '$username'");
    if(mysqli_fetch_assoc($check)) {
        echo "<script>alert('Username sudah dipakai')</script>";
        return 0;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $str = "INSERT INTO accounts VALUES ('NULL', '$username','$password', '$firstname', '$lastname')";
    mysqli_query($connectionID,$str);
    return 1;
}

function Query($query){
    global $connectionID;
    $result = mysqli_query($connectionID, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function IsGroupOwner($groupid, $userid){
    global $connectionID;

    $posdata = Query(
        "SELECT
        * 
        FROM positions pos
        JOIN accounts_groups ag
        ON ag.groupID = pos.groupID
        WHERE ag.accountID = $userid AND ag.groupID = $groupid
    ");
    
    if($posdata[0]["positionValue"] == 1)
        return true;

    return false;
}

function GetGroupOwnerID($groupid){
    global $connectionID;


    $result = Query("
        SELECT * FROM positions p 
        JOIN groups g 
        ON g.groupID = p.groupID
        WHERE g.groupID = $groupid AND positionValue = 1;
    ");

    // return result[0][""]
    
}