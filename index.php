<?php

    require_once "functions.php";

    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $accountID = $userdata["accountID"];
    
    $groups = GetGroupDataByUID($accountID);
    $groupIterator = 0;

    $assignments = Query(    
    "SELECT *
    FROM assignments
    WHERE assignedTo = $accountID
    ORDER BY assignmentStatus ASC, assignmentDeadline ASC
    ");
    
    $asgIterator = 0;
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
        /* height: 26rem; */
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="group/mygroup.php">Groups</a>
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
                                    <form action="logout.php" class="margin-right:5rem" ethod=" post">
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
    <div class="content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-sm-1">
                    <h1 class="fs-1"><i class="bi bi-person-circle"></i></h1>
                </div>
                <div class="col d-flex align-items-center ps-0">
                    <h3 class="fs-3">Hello, <?= GetUserFullName($accountID) ?> </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <a href="group/addgroup.php" class="btn btn-success"><i class="bi bi-people-fill"></i>
                        │ Add Group
                    </a>
                </div>
            </div>

            <div class="row text-center">
                <div class="col">
                    <h4 class="fw-bolder">My groups</h4>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Members</th>
                                <th scope="col">Position</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($groups as $groupData) :?>

                            <?php 
                                $groupIterator++;
                                $myQuery = "
                                SELECT 
                                    COUNT(*) as `members`
                                FROM accounts_groups 
                                WHERE groupID =" . $groupData["groupID"];
                                
                                $tmpQuery = Query($myQuery);
                                $groupMembers = $tmpQuery[0]["members"];
                                
                                $myQuery = "
                                SELECT * from accounts_groups ag
                                JOIN positions p
                                ON p.positionID = ag.positionID
                                WHERE ag.groupID = " . $groupData["groupID"] . " AND ag.accountID = ". $userdata["accountID"];                             
                                $positions = Query($myQuery);
                            ?>

                            <tr>
                                <?php $groupid = $groupData["groupID"]; ?>
                                <th scope="row"><?= $groupIterator ?></th>
                                <td scope="row"><?= $groupData["groupName"]; ?></td>
                                <td scope="row"><?= $groupMembers?></td>
                                <td scope="row"><?= $positions[0]["positionName"] ?></td>

                                <td scope="row">
                                    <a href='group/group.php?groupid=<?= $groupid?>'>
                                        <button type="submit" class="btn btn-primary" value><i
                                                class="bi bi-arrow-right-square"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <h4 class="fw-bolder">Upcoming Assignments</h4>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Group</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($assignments as $asgData) :?>
                            <?php $asgIterator++;                                                                  
                                $tmpProcessStr = "width: " . GetStatusNameByID($asgData["assignmentStatus"]). "%";                           
                            ?>
                            <tr>
                                <?php 
                                    $asgRowGroupID = $asgData["groupID"];
                                    $asgRowID = $asgData["assignmentID"];
                                    $dataPackage = $asgRowID . "_" . $asgRowGroupID;
                                ?>
                                <th><?= $asgIterator ?></th>
                                <td><?= $asgData["assignmentTitle"] ?></td>
                                <td><?= GetGroupNameByID($asgRowGroupID) ?></td>
                                <td><?= $asgData["assignmentDeadline"] ?></td>

                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="<?= $tmpProcessStr; ?>" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a
                                        href="assignment/detailassignment.php?<?= "groupid=" . $asgRowGroupID . "&" ."asgid=" . $asgRowID?>">
                                        <button class="btn btn-primary" type="button"><i class="bi bi-eye-fill"></i>
                                        </button>
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End of contents -->
    <svg xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;">
        </path>
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