<?php
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Halo Semua</h1>
    <form action="logout.php" method="post">
        <button type="submit" name="logout" onclick="return confirm('Confirm Logout');">Logout</button>
    </form>

</body>
</html>