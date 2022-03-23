<?php

    require_once "functions.php";

    StartLoginSession();
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
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
                        <a class="nav-link" href="mygroup.php">Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Assignments</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>
                </ul>

                <form action="logout.php" class="margin-right:5rem" ethod=" post">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end ps-5  ">
                        <button type="submit" class="btn btn-danger btn-sm" name="logout">Logout</button>
                    </div>

                </form>
            </div>
        </div>
    </nav>

    <!-- End of Navbar -->

    <!-- Contents -->
    <div class="content">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <a href="addgroup.php" class="btn btn-primary"><i class="bi bi-people-fill"></i>
                        Add Group
                    </a>
                </div>
            </div>

            <div class="row text-center">
                <div class="col">
                    <h4 class="fw-bolder">My groups</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Members</th>
                                <th scope="col">Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $myQuery = 
                                "
                                SELECT 
                                    g.groupName,
                                    g.groupID
                                FROM groups g
                                JOIN accounts_groups ag
                                ON g.groupID = ag.groupID
                                WHERE ag.accountID =" . $userdata["accountID"];

                                $result = mysqli_query($connectionID, $myQuery);
                                $iterator = 0;

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $iterator++;
                                    echo "<tr>";
                                    echo "<th scope=\"row\">" . $iterator . "</th>";
                                    echo "<td>" . $row["groupName"] . "</td>";

                                    $myQuery = "
                                        SELECT 
                                            COUNT(*) as `members`
                                        FROM accounts_groups 
                                        WHERE groupID =" . $row["groupID"];

                                    $resultrow = mysqli_query($connectionID, $myQuery);
                                    $groupMembers = mysqli_fetch_assoc($resultrow);
                                    
                                    echo  "<td>" . $groupMembers["members"] . "</td>";
                                    
                                    $myQuery = "
                                        SELECT
                                            positionName,
                                            ag.groupID  
                                        FROM positions pos
                                        JOIN accounts_groups ag
                                        ON ag.groupID = pos.groupID
                                        WHERE ag.accountID = ". $userdata["accountID"] . " AND ag.groupID = " . $row["groupID"];
                                        ;
                                    
                                    $resultrow = mysqli_query($connectionID, $myQuery);
                                    $posName = mysqli_fetch_assoc($resultrow);                                
                                    
                                    echo  "<td>" . $posName["positionName"] . "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <h4 class="fw-bolder">Upcoming Assignments</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Created on</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php 
                                $myQuery =  
                                "
                                    SELECT DISTINCT
                                        asg.assignmentTitle,
                                        asg.assignmentDeadline,
                                        asg.assignmentCreated,
                                        asg.assignmentStatus,
                                        asg.assignedTo
                                    FROM assignments asg
                                    JOIN groups g 
                                    ON g.groupID = asg.groupID
                                    JOIN accounts_groups ag
                                    ON ag.groupID = g.groupID
                                    WHERE asg.assignedTo = " . $userdata["accountID"];
                                

                                $result = mysqli_query($connectionID, $myQuery);
                                $iterator = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $iterator++;
                                    echo "<tr>";
                                    echo "<th scope=\"row\">" . $iterator . "</th>";
                                    echo "<td>" . $row["assignmentTitle"] . "</td>";
                                    echo "<td>" . $row["assignmentCreated"] . "</td>";
                                    echo "<td>" . $row["assignmentDeadline"] . "</td>";
                                    
                                    if($row["assignmentStatus"] == 0) 
                                        echo "<td class=\"text-danger\">" . GetStatusNameByID($row["assignmentStatus"]) . "</td>";
                                    if($row["assignmentStatus"] > 0) 
                                        echo "<td class=\"text-warning\">" . GetStatusNameByID($row["assignmentStatus"]) . "</td>";                                            
                                    echo "</tr>";
                                }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
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