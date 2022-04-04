<?php

    require_once "../functions.php";

    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $accountID = $userdata["accountID"];
    $unopenedNotifsSize = GetUnopenedNotifsSize($accountID);
    
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
    .content {
        margin-top: 10vh;
        height: 26rem;
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <?php include "../header.php" ?>

    <!-- Contents -->

    <div class="container">

        <div class="row">
            <h4 class="mt-4 mb-4">Add Group</h4>
        </div>

        <?php 
            if(isset($_POST["add-button"])){
                
                $db_name = DB_NAME;

                $result = mysqli_query($connectionID, "SELECT AUTO_INCREMENT as `AI`
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = \"$db_name\"
                AND TABLE_NAME = \"groups\"");
                
                if(strlen($_POST["group-name"]) < 5){
                    echo '
                    <div class="alert alert-danger" role="alert">
                    Your group name is less than 5 characters.
                    </div>';
                } else {
                    $autoIncrementGroupVal = mysqli_fetch_assoc($result);
                
                    $result = mysqli_query($connectionID, "SELECT AUTO_INCREMENT as `AI`
                    FROM information_schema.TABLES
                    WHERE TABLE_SCHEMA = \"$db_name\"
                    AND TABLE_NAME = \"positions\"");
                    
                    $autoIncrementPosVal = mysqli_fetch_assoc($result);

                    $myQuery = 
                    "INSERT INTO `groups` (`groupID`, `groupOwner`, `groupName`, `groupDetail`) VALUES (NULL, '" .
                    $userdata["accountID"] . "', '" . $_POST["group-name"] . "', '" . $_POST["group-description"] . "');";
                    mysqli_query($connectionID, $myQuery);

                    $myQuery = 
                    "INSERT INTO `positions` (`positionID`, `groupID`, `positionName`, `positionValue`) VALUES (NULL, " . $autoIncrementGroupVal["AI"] . 
                    ", 'Owner', '1');";
                    mysqli_query($connectionID, $myQuery );


                    $myQuery = 
                    "INSERT INTO `accounts_groups` (`accountID`, `groupID`, `positionID`) VALUES ('".$userdata["accountID"] . "' , '" .$autoIncrementGroupVal["AI"] .  "' , " . $autoIncrementPosVal["AI"] .");";
                    
                    mysqli_query($connectionID, $myQuery);
                
                    echo 
                    '<div class="alert alert-success" role="alert" id="success-message">
                    Successfully created a group!
                    </div>';               
                }

            }   
        
        ?>

        <div class="row">
            <form action="" method="post" name="add-group">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-people-fill"></i></span>
                    <input type="text" class="form-control" placeholder="Group Name" aria-label="Username"
                        aria-describedby="basic-addon1" name="group-name">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Description</span>
                    <textarea class="form-control" aria-label="With textarea" ame="group-description"
                        name="group-description"></textarea>
                </div>

                <button type=" submit" class="btn btn-success" name="add-button">Add Group</button>
            </form>
        </div>
    </div>

    <!-- End of contents -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;"></path>
    </svg>
    <!-- Footer -->
    <footer class="bg-dark text-white  pb-5">
        <p class="font-weight-bold text-center fs-5">Created by : Tesla Team</p>
    </footer>


    <!-- End of Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>