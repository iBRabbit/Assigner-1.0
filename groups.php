<?php
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group</title>
    <style>
        body{
            display : flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .header{
            display : flex;
            justify-content : space-between;
            flex-direction: row;
            align-items : center;
            padding-inline : 2vw;
            border-bottom: 1px solid black;
        }

        .header-right {
            display:flex;
            flex-direction:row;
            align-items : center;
            
        }

        .header-right a, .header-right form{
            padding-inline:1vw;
        }

        a {
            text-decoration:none;
        }

        form > button {
            background-color:red;
            border-radius:13px;
            border:0px solid black;
            padding:5px;
            color:white;
        }
        
        form > button:hover {
            background-color:darkred;
        }

        .content {
            display : flex;
            flex-direction: row;
            justify-content : space-around;
            height : 60vh;
            margin-inline: 10vw;
            margin-block : 10vh;
        }

        .content h3 {
            text-align : center;
        }
        
        .content-left {
            border : 1px black solid;
            width : 50%;
        }

        .content-right {
            border : 1px black solid;
            width : 50%;
        }

        table th {
            padding-inline : 2vw;
        }

        table {
            align-items : center;
            text-align : center;
            
        }

        .footer {
            border-top : 1px solid black;
            text-align : center;
            align-items : center;
        }

    </style>
</head>
<body>
<div class="header">
        <div class="header-left">
            <h1>Assigner</h1>
        </div>

        <div class="header-right">   
            <a href="index.php">Home</a>
            <a href="">My Profile</a>
            <a href="groups.php">My groups</a>
            <a href="">Notifications</a>
            <form action="logout.php" method="post">
                <button type="submit" name="logout" onclick="return confirm('Confirm Logout');">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        <h3>Groups</h3>
    </div>

    <div class="footer">
        <p>Created by : TESLA TEAM</p>
    </div>
</body>
</html>