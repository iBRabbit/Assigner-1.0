<?php 
    session_start();
    require_once 'functions.php'; 

    if(isset($_POST["signup-button"])){
        if(ValidateRegister($_POST) === 1) {
            echo "<script>alert('User baru berhasil Ditambahkan!');</script>";
        }
        else {
            echo mysqli_error($connectionID);
        }
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

    #signup-card {
        border-radius: 30px;
    }

    .container {
        height: 30rem;
    }

    .signup-box {
        display: flex;
        flex-direction: column;
        margin: auto;
        margin-top: 10%;
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <!-- signup Card -->

    <div class="signup-box">
        <div class="container">
            <div class="row">
                <div class="card text-center" id="signup-card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title fw-bold">Signup</h4>
                        <p class="card-text">Enjoy our features at Assigner!</p>

                        <form action="" method="post" name="register-account">

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

                            <form action="">
                                <button type="submit" name="signup-button" class="btn btn-success mb-3">Join
                                    Now</button>
                            </form>

                        </form>

                        <form action="login.php">
                            <button type="submit" name="login-button" class="btn btn-outline-primary">Back
                                Login</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- End of signup card -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>