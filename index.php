<?php

    require_once "functions.php";

    session_start();
    if(!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }

    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner</title>

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
            <a href="groups.php">My Profile</a>
            <a href="">My groups</a>
            <a href="">Notifications</a>
            <form action="logout.php" method="post">
                <button type="submit" name="logout" onclick="return confirm('Confirm Logout');">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="content-left">
            <h3>My Groups</h3>

            <div class="content-left-groups">
                <table>
                    <tr>
                        <th>Group Name</th>
                        <th>Group Members</th>
                        <th>Position</th>
                    </tr>

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

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>" . "<td>" . $row["groupName"] . "</td>";

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
                                *
                                FROM positions pos
                                JOIN accounts_groups ag
                                ON ag.positionID = pos.positionID
                                WHERE ag.accountID = ". $userdata["accountID"] . " AND ag.groupID = " . $row["groupID"];
                                ;
                            
                            $resultrow = mysqli_query($connectionID, $myQuery);
                            $posName = mysqli_fetch_assoc($resultrow);                                
                            
                            echo  "<td>" . $posName["positionName"] . "</td>";
                            echo "</tr>";
                        }

                    ?>
                </table>
            </div>

        </div>

        <div class="content-right">
            <h3>Upcoming Assignments</h3>
            <div class="content-right-assignments">
                <table>
                    <tr>
                        <th>Assignment Title</th>
                        <th>Created on</th>
                        <th>Deadline</th>
                        <th>Status</th>

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
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>" . "<td>" . $row["assignmentTitle"] . "</td>";
                                echo "<td>" . $row["assignmentCreated"] . "</td>";
                                echo "<td>" . $row["assignmentDeadline"] . "</td>";
                                echo "<td>" . GetStatusNameByID($row["assignmentStatus"]) . "</td>";
                                echo "</tr>";
                            }
                        ?>

                    </tr>
                </table>
            </div>
        </div>

    </div>

    <div class="footer">
        <p>Created by : TESLA TEAM</p>
    </div>


</body>
</html>