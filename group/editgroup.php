<?php

    require_once "../functions.php";

    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $groupid = $_GET["groupid"];
    $accountID = $userdata["accountID"];
    $members = GetMemberListByGroupID($groupid);
    $allPosition = GetAllGroupPositions($groupid);
    $unopenedNotifsSize = GetUnopenedNotifsSize($accountID);
    
    ValidateGroupLink($accountID, $groupid, "../index.php",true);

    $rows = Query(
        "SELECT *
        FROM groups
        WHERE groupID = '$groupid'");
        $rows = $rows[0];
    
    
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
            <h4 class="mt-4 mb-4">Edit Group</h4>
        </div>

        <div class="row">
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
                        $groupName = $_POST["group-name"];
                        $groupDesc = $_POST["group-description"];
                        mysqli_query($connectionID,"UPDATE groups SET groupName='$groupName', groupDetail='$groupDesc' WHERE groupID = $groupid");
                        echo 
                        '<div class="alert alert-success" role="alert" id="success-message">
                        Successfully created a group!
                        </div>';               
                    }
                }
            ?>
        </div>

        <div class="row">
            <form action="" method="post" name="add-group">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-people-fill"></i></span>
                    <input type="text" class="form-control" value="<?= $rows["groupName"] ?>" aria-label="Username"
                        aria-describedby="basic-addon1" name="group-name" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Description</span>
                    <input class="form-control" aria-label="With textarea" ame="group-description"
                        name="group-description" value="<?= $rows["groupDetail"] ?>"></input>
                </div>
                <button type=" submit" class="btn btn-success" name="add-button">Edit Group</button>
            </form>
        </div>
        
        <div class="row">
            <h4 class="mt-3 mb-3">Members to be assigned</h4>
        </div>

        <div class="row">
            <?php 
                if(isset($_POST["add-pos-button"])){
                    if($_POST["add-position"] == -1){
                        echo 
                        '<div class="alert alert-danger" role="alert" id="success-message">
                        Please pick position after
                        </div>';
                    }
                    else {
                        $posValue = $_POST["add-position"]+1;
                        mysqli_query($connectionID,"UPDATE positions SET positionValue=positionValue+1 WHERE groupID = $groupid AND positionValue >= $posValue;");
                        $posName = $_POST["add-position-name"];
                        mysqli_query($connectionID,"INSERT INTO positions VALUES ('','$groupid','$posName','$posValue');");
                        echo 
                        '<div class="alert alert-success" role="alert" id="success-message">
                        Success Add Position
                        </div>';
                    }
                }
            ?>
        </div>

        <div class="row mb-3">
            <form action="" method="post">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInputGrid" placeholder="Add Position Name"
                            name="add-position-name" required>
                        <label for="floatingInputGrid">Add Position Name (*)</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid" aria-label="Floating label select example"
                            name="add-position">
                            <option value=-1 selected>Position</option>
                            <?php foreach($allPosition as $pos) :?>
                            <?php $val = "\"" . $pos["positionValue"] . "\"";?>
                            <option value=<?= $val?>><?= $pos["positionName"] ?>
                            </option>
                            option value=>""</option>";
                            <?php endforeach; ?>
                        </select>
                        <label for="floatingSelectGrid">Add Position After (*)</label>
                        </div>
                    </div>
                </div>
                <button button type=" submit" class="btn btn-success" name="add-pos-button">Add Position </button>
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