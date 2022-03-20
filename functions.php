<?php 

$connectionID = mysqli_connect("localhost","root","","seproject");

if ($connectionID -> connect_errno) {
    echo "Failed to connect to MySQL: " . $connectionID -> connect_error;
    exit();
} 

function validate($input) {
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

?>