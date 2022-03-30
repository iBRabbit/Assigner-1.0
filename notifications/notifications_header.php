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
        /* height: 26rem; */
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <?php include "../header.php" ?>
    
    <!-- Contents -->

    <div class="container">
        <div class="row">
            <div class="col ">

                <?php  
                    if(isset($_POST["accept-invite-btn"])) {
                        parse_str($_POST["accept-invite-btn"], $arr);
                            $groupid = $arr["groupid"];
                            $posid = $arr["posid"];
                            $notifid = $arr["notifid"];
                            
                            mysqli_query($connectionID, "INSERT INTO accounts_groups VALUES ('$accountID', '$groupid', '$posid')");

                            mysqli_query($connectionID, "DELETE FROM invites WHERE accountID = $accountID AND inviteGroupID = $groupid");
                            
                            mysqli_query($connectionID, "UPDATE notifications SET notificationOpened = 1 WHERE notificationID = $notifid");
                            Refresh();
                    }
                            
                    
                ?>

                <div class="row">
                    <h4 class="mt-4 mb-4"> New Notifications</h4>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="list-group">

                    <?php 
                        $notifications = GetAllNotifsByUID($accountID, true);
                    ?>

                    <?php foreach($notifications as $notif) :?>

                    <a href="#" class="list-group-item list-group-item-action >" aria-current="true">
                        
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= $notif["notificationTitle"] ?></h5>
                            <small>3 days ago</small>
                        </div>
                        <p class="mb-1"><?= $notif["notificationMessage"] ?></p>
                        <?php if($notif["notificationType"] == 1) :?>
                            <?php if($notif["notificationOpened"] == 0) :?>
                                <?php 
                                    parse_str($notif["notificationValue"], $arr);
                                    $groupid = $arr["groupid"];
                                    $posid = $arr["posid"];                        
                                ?>
                                <form action="notifications_header.php" method="post">
                                    <button type="submit" class="btn btn-success" name="accept-invite-btn"
                                        value="<?= "groupid=".$groupid . "&posid=" . $posid ."&notifid=" . $notif["notificationID"]?>">Accept</button>
                                    <button type="submit" class="btn btn-outline-danger"
                                        name="decline-invite-btn">Decline</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class=" row mt-4 mb-4">
            <h4>Passed notifications</h4>
        </div>

        <div class="row">
            <div class="col">
                <div class="list-group">

                    <?php 
                        $notifications = GetAllNotifsByUID($accountID, true, true);
                    ?>

                    <?php foreach($notifications as $notif) :?>
                    <a href="#" class="list-group-item list-group-item-action >" aria-current="true">
                        
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= $notif["notificationTitle"] ?></h5>
                            <small>3 days ago</small>
                        </div>
                        <p class="mb-1"><?= $notif["notificationMessage"] ?></p>
                        
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <?php ClearNotificationsFromID($accountID); ?>

    <!-- End of contents -->
    <svg xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
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