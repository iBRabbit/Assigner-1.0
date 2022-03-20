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
    
        .login-box {
            display : flex;
            flex-direction: column;
            align-items : center;
            margin-block : auto;
            width:25%;
            /* height:30vh; */
            margin: auto;
            margin-top: 10%;
            border-radius: 20px;
            border: 1px solid black;
        }

        .form-login , .form-signup{
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

        .form-login input ,.form-login button, .form-signup button{
            display : flex;
            flex-direction: column;
            align-items : center;
            margin-block : 1vh;
        }

    </style>


    <title>asdsadas</title>
</head>
<body>
    
    <div class="login-box">
        <h1>Assigner Login</h1>
        <div class="form-box">
            <form action="index.php" method = "post" class = "form-login">
                <input type="text" name = "input-username" placeholder = "Username">
                <input type="text" name = "input-password" placeholder = "Password">
                <button type name = "login-button">Login</button>
            </form>

            <form action="signup.php" class = "form-signup">
                <button type name = "signup-button">Sign Up</button>
            </form>

        </div>
    </div>


</body>
</html>