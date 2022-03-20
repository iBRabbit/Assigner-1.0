<?php 
    session_start();
    require_once 'functions.php'; 

    if(isset($_POST["signup-button"])){
        if(validate($_POST) === 1) {
            echo "<script>alert('User baru berhasil Ditambahkan!');</script>";
        }
        else {
            echo mysqli_error($connectionID);
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bodystyle.css">

    <style>

        body{
            display : flex;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            
        }
       
        .signup-box {
            display : flex;
            flex-direction: column;
            align-items : center;
            margin-block : auto;
            text-align : center;
            width:30%;
            margin: auto;
            margin-top: 10%;
            border-radius: 20px;
            border: 1px solid black;
        }

        .form-signup{
            display : flex;
            flex-direction: column;
            align-items : center;
            justify-content : center;
            
        }

        .form-box{
            margin: auto;
            text-align: center;
            margin-block: 3vh;
            
        }

        .form-signup input ,.form-signup button, .form-signup button{
            display : flex;
            flex-direction: column;
            align-items : center;
            margin-block : 1vh;
            margin-inline:3vw;
        }

    </style>


    <title>asdsadas</title>
</head>
<body>
    
    <div class="signup-box">
        <h1>Assigner Signup</h1>
        <div class="form-box">
            <form action="" method = "post" class = "form-signup" name = "register-account">
                <input type="text" name = "input-username" placeholder = "Username" required>
                <input type="password" name = "input-password" placeholder = "Password" required>
                <button type="submit" name = "signup-button">Register</button>
            </form>
            <form action="login.php" class = "form-login">
                <button type="submit" name = "login-button">Back Login</button>
            </form>

        </div>
    </div>


</body>
</html>