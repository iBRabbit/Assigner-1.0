<?php
session_start();
require 'functions.php';


if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}



?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Boostrap Icons  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- My CSS -->
    <style>
    body {
        display: flex;
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;

    }

    #login-card {
        border-radius: 30px;
    }

    .container {
        height: 30rem;
    }

    .login-box {
        display: flex;
        flex-direction: column;
        margin: auto;
        margin-top: 10%;
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <!-- Login Card -->

    <div class="login-box">
        <div class="container">
            <div class="row">
                <div class="card text-center" id="login-card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title fw-bold">Login</h4>
                        <p class="card-text">Login or Register to Assigner!</p>

                        <?php 
                            if (isset($_POST["login-button"])) {
                                $check = false;
                                $username = $_POST["input-username"];
                                $password = $_POST["input-password"];
                            
                                $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE username = '$username'");
                            
                                if(mysqli_num_rows($result) === 1) {
                                    $row = mysqli_fetch_assoc($result);
                                    $uid = $row["accountID"];
                                    if(password_verify($password, $row["password"])) {
                                        $_SESSION["login"] = true;
                                        $_SESSION["username"] = $username;
                                        $_SESSION["uid"] = $uid;
                                        $check = true;
                                        echo " <div class=\"alert alert-danger\" role=\"alert\">
                                        Your username or password is incorrect.
                                        </div>";
                                        header("Location: index.php");
                                        exit;
                                    }
                                    else {
                                        echo " <div class=\"alert alert-danger\" role=\"alert\">
                                        Your username or password is incorrect.
                                        </div>";
                                    }
                                }
                                else {
                                    echo " <div class=\"alert alert-danger\" role=\"alert\">
                                    Your username or password is incorrect.
                                    </div>";
                                }
                            }
                        
                        ?>


                        <form action="" method="post" ">
                            <!-- <input type=" text" name="input-username" placeholder="Username"
                            class="form-control mt-3 mb-3" required> -->

                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">@</span>
                                <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon1" name="input-username" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"> #</span>
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                    aria-describedby="basic-addon1" name="input-password" required>
                            </div>

                            <button type="submit" name="login-button" class="btn btn-primary mb-3">Login</button>
                        </form>
                        <form action="signup.php" class="form-signup">
                            <button type="submit" name="signup-button" class="btn btn-outline-success">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- End of Login card -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>