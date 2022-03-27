<?php

    require_once "../functions.php";

    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $accountID = $userdata["accountID"];

    $groupid = $_GET["groupid"];
    ValidateGroupLink($accountID, $groupid, "../index.php", true);
    $unopenedNotifsSize = GetUnopenedNotifsSize($accountID);
    
    $positions = GetAllGroupPositions($groupid);

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
    }

    #content-container {
        width: 60%;
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Assigner</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mygroup.php">Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Assignments</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= GetUserFullName($accountID) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">

                            <li><a class="dropdown-item" href="#">My Profile</a></li>
                            <li>
                                <a class="dropdown-item " href="#">
                                    <form action="../logout.php" class="margin-right:5rem" ethod=" post">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                name="logout">Logout</button>
                                        </div>
                                    </form>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <!-- End of Navbar -->

    <!-- Contents -->

    <div class="container">

        <div class="row">
            <h4 class="mt-4 mb-4">Invite Group Member</h4>
        </div>

        <?php 
            if(isset($_POST["member-invite-btn"])){ 
                $userid = IsUsernameExists($_POST["member-username"]);

                if(empty($_POST["member-username"])) 
                    echo "
                    <div class=\"alert alert-danger\" role=\"alert\">
                    You must fill the username form.
                    </div>";
                else if($userid === -1)
                    echo "
                    <div class=\"alert alert-danger\" role=\"alert\">
                    Username does not exists.
                    </div>";
                else if(IsGroupMember($userid, $groupid))
                    echo "
                    <div class=\"alert alert-danger\" role=\"alert\">
                    This member is already a member of this group.
                    </div>";
                else {

                    if(IsAlreadyInvitedToGroup($userid, $groupid)) 
                        echo "
                        <div class=\"alert alert-danger\" role=\"alert\">
                        This member is already invited to this group.
                        </div>";
                    else {

                        $groupname = GetGroupNameByID($groupid);
                        $inviter = GetUserFullName($accountID);
                        $posid = $_POST["member-pos"];
               
                        
                        mysqli_query($connectionID, "INSERT INTO invites VALUES ('', '$accountID', '$userid', '$groupid')");
                        
                        mysqli_query($connectionID, "INSERT INTO notifications VALUES ('', '$userid', 'Group Invite', '1', '0', 'You have received a group invite to group $groupname by $inviter', 'groupid=$groupid&posid=$posid')");
                        
                        echo '
                        <div class="alert alert-success" role="alert">
                        Successfully invited a member.
                        </div>';      
                            
                    }
                }
            }   

        ?>

        <div class="row">
            <form action="" method="post" name="add-group">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" placeholder="username" aria-label="Username"
                        aria-describedby="basic-addon1" name="member-username">
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Floating label select example"
                        name="member-pos">
                        <option selected>Select Group Position </option>
                        <?php foreach($positions as $pos) :?>

                        <?php $val = "\"" . $pos["positionID"] . "\"";?>

                        <option value=<?= $val?>><?= $pos["positionName"]; ?>
                        </option>
                        option value=>""</option>";

                        <?php endforeach; ?>
                    </select>
                    <label for="floatingSelectGrid">Member Position (*)</label>
                </div>

                <button type="submit" class="btn btn-success" name="member-invite-btn">Invite</button>
            </form>
        </div>
    </div>

    <!-- End of contents -->

    <!-- Footer -->

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;"></path>
    </svg>

    <footer class="bg-dark text-white  pb-5">
        <p class="font-weight-bold text-center fs-5">Created by : Tesla Team</p>
    </footer>


    <!-- End of Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>