<?php
    require_once "../functions.php";
    StartLoginSession();
    
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $groupid = $_GET["groupid"];
    $accountID = $userdata["accountID"];

    ValidateGroupLink($accountID, $groupid, "../index.php");

    $rows = Query(
    "SELECT groupName, groupDetail, groupOwner
    FROM groups
    WHERE groupID = '$groupid'");
    $rows = $rows[0];

    $assignments = GetAssignmentListByGroupID($groupid);
    $memberList = GetMemberListByGroupID($groupid);
    
    if(isset($_POST["delete-asg-btn"])){
        $asgID = $_POST["delete-asg-btn"];
        RemoveAssignment($asgID);
        Refresh();  
    }
    
    $unopenedNotifsSize = GetUnopenedNotifsSize($accountID);  
    
    $jumlahDataPerHalaman = 5;
    $jumlahHalamanAsg = ceil(count($assignments)/$jumlahDataPerHalaman);
    $halamanAktifAsg = (isset($_GET["pageAsg"])) ? $_GET["pageAsg"] : 1;
    $awalDataAsg = $jumlahDataPerHalaman * $halamanAktifAsg - $jumlahDataPerHalaman;
    $assignments = Query("SELECT * FROM assignments asg JOIN asg_member am ON am.assignmentID = asg.assignmentID
    WHERE am.asgMemberAccountID = $accountID ORDER BY am.asgMemberProgress ASC, asg.assignmentDeadline ASC LIMIT $awalDataAsg, $jumlahDataPerHalaman;");

    $jumlahHalamanMember = ceil(count($memberList)/$jumlahDataPerHalaman);
    $halamanAktifMember = (isset($_GET["pageMember"])) ? $_GET["pageMember"] : 1;
    $awalDataMember = $jumlahDataPerHalaman * $halamanAktifMember - $jumlahDataPerHalaman;
    $memberList = Query("SELECT * FROM accounts_groups ag JOIN accounts ac ON ac.accountID = ag.accountID JOIN positions pos ON pos.positionID = ag.positionID
    WHERE ag.groupID = $groupid LIMIT $awalDataMember, $jumlahDataPerHalaman;");
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

    #assignment-list {
        width: 50%;
    }

    #add-asg-btn,
    #add-memlist-btn {
        display: flex;
        justify-content: flex-end;
        /* margin: 1rem; */
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <?php include "../header.php" ?>
    
    <!-- Contents -->
    <div class="content">
        <div class="container">

            <div class="row mb-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <?= "A Group Owned by  " . GetUserFullName(GetGroupOwnerID($groupid)) ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $rows["groupName"]; ?></h5>
                            <p class="card-text">
                                Description:
                                <br>
                                <?= $rows["groupDetail"]; ?>
                            </p>

                            <?php if(IsGroupOwner($accountID, $groupid)) : ?>
                            <a href="editgroup.php?groupid=<?= $groupid?>" class="btn btn-primary"><i class="bi bi-pencil-square"></i> │ Edit
                                Group</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table table align-middle">
                        <div class="assignment-header">
                            <div class="container p-0 ">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="">Group Assignments</h3>
                                    </div>
                                    <div class="col " id="add-asg-btn">

                                        <!-- <a href="../assignment/addassignment.php?groupid=<?=$groupid ?>"> -->

                                        <?php if(IsGroupOwner($accountID, $groupid)) : ?>
                                        <!-- <button type="submit" class="btn btn-success"><i class="bi
                                                    bi-plus-circle-fill"></i> │ Add Assignment
                                            </button> -->

                                        <!-- Example single danger button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi
                                                    bi-plus-circle-fill"></i> │ Add Assignment
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="../assignment/addassignment_single.php?groupid=<?=$groupid ?>">Individual
                                                        Assignment</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="../assignment/addassignment_group.php?groupid=<?=$groupid ?>">Group
                                                        Assignment</a></li>
                                            </ul>
                                        </div>
                                        <?php endif; ?>

                                        <!-- </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Assigned to</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Progress</th>
                                <th scope="col-2" colspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($assignments as $asg) :?>
                            <?php 
                                $i++;
                                $members = GetAssignmentMembers($asg["assignmentID"], $asg["assignmentIsGroup"]);
                            ?>
                            <tr>
                                <td><?= $i+$awalDataAsg ?></td>
                                <td><?= $asg["assignmentTitle"]; ?></td>
                                <?php if($asg["assignmentIsGroup"]) ?>
                                <td>

                                    <?php if($asg["assignmentIsGroup"]) :?>
                                    <ul class="list-group list-group-flush list-group-numbered">
                                        <?php foreach($members as $mem) :?>
                                        <li class="list-group-item"><?= GetUserFullName($mem["asgMemberAccountID"]) ?>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>

                                    <?php if(!$asg["assignmentIsGroup"]) :?>
                                    <ul class="list-group list-group-flush list-group-numbered">
                                        <li class="list-group-item">
                                            <?= GetUserFullName($members["asgMemberAccountID"]) ?>
                                        </li>
                                    </ul>
                                    <?php endif; ?>
                                </td>

                                <td><?= $asg["assignmentDeadline"] ?></td>

                                <?php 
                                    if($asg["assignmentIsGroup"]) 
                                        $tmpProcessStr = "width: " . CountTotalAssignmentProgress($asg["assignmentID"]). "%";      
                                    else 
                                        $tmpProcessStr = "width: ".CountTotalAssignmentProgress($asg["assignmentID"]). "%";
                                ?>

                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="<?= $tmpProcessStr; ?>" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <?php 
                                        $asgRowID = $asg["assignmentID"]; 
                                        $asgRowGroupID = $asg["groupID"];
                                        ?>
                                        <?php if(IsAsgAssignedToID($accountID, $asgRowID) || IsGroupOwner($accountID,
                                        $asgRowGroupID)): ?>
                                        <a href="../assignment/detailassignment.php?groupid=<?=$asgRowGroupID."
                                            &asgid=" . $asgRowID?>">
                                            <button class="btn btn-primary" type="button"><i class="bi bi-eye-fill"></i>
                                            </button>
                                        </a>
                                        <?php endif; ?>

                                        <?php if(IsGroupOwner($accountID, $groupid)): ?>
                                        <form action="group.php?groupid=<?= $groupid?>" method="post"><button class="btn btn-danger" type="submit"
                                                name="delete-asg-btn" value="<?=$asgRowID?>"><i
                                                    class="bi bi-trash3-fill"></i></button></form>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                        <?php if($jumlahHalamanAsg == 0):;
                        else: ?>
                            <?php if($halamanAktifAsg == 1):
                                $pageAkhir = $halamanAktifAsg+2; $pageAwal=$halamanAktifAsg?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php else:
                                if($halamanAktifAsg == $jumlahHalamanAsg){ $pageAwal=$halamanAktifAsg-2; $pageAkhir = $halamanAktifAsg;}
                                else{ $pageAwal = $halamanAktifAsg-1; $pageAkhir = $halamanAktifAsg+1;}?>
                                <li class="page-item"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg-1;?>&pageMember=<?= $halamanAktifMember; ?>&groupid=<?= $groupid;?>">Previous</a></li>
                            <?php endif; ?>
                            <?php if($jumlahHalamanAsg == 1 || $jumlahHalamanAsg == 2){$pageAwal = 1;$pageAkhir = $jumlahHalamanAsg;}?>
                            <?php for($i=$pageAwal; $i<=$pageAkhir ;$i++): ?>
                                <?php if($i == $halamanAktifAsg):?>
                                    <li class="page-item active" aria-current="page"><a class="page-link" href="?pageAsg=<?= $i;?>&pageMember=<?= $halamanAktifMember; ?>&groupid=<?= $groupid;?>"><?= $i;?></a></li>
                                <?php else:?>
                                    <li class="page-item"><a class="page-link" href="?pageAsg=<?= $i;?>&pageMember=<?= $halamanAktifMember; ?>&groupid=<?= $groupid;?>"><?=$i?></a></li>
                                <?php endif;?>
                            <?php endfor; ?>
                            <?php if($halamanAktifAsg == $jumlahHalamanAsg):?>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            <?php else:?>
                                <li class="page-item"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg+1?>&pageMember=<?= $halamanAktifMember; ?>&groupid=<?= $groupid;?>">Next</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class=" col">
                    <table class="table align-middle">
                        <div class="assignment-header">
                            <div class="container p-0 ">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="">Member List</h3>
                                    </div>

                                    <?php if(IsGroupOwner($accountID, $groupid)) :?>
                                    <div class="col" id="add-asg-btn">
                                        <a href="addgroupmember.php?groupid=<?=$groupid?>" class="btn btn-success"><i
                                                class="bi bi-plus-circle-fill"></i> │ Add Member</a>
                                    </div>
                                    <?php endif; ?>


                                </div>
                            </div>
                        </div>
                        <thead>
                            <tr>
                                <!-- name, username, position -->
                                <th scope=" col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Position</th>
                                <th scope="col-2" colspan="2" class="text-center">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Button Eye nantinya akan ngasih liat isi profil dari user tersebut -->
                            <!-- Nanti di dalamnya bakal ada edit position juga -->
                            <!-- Design nyusul -->
                            <?php $i=1?>
                            <?php foreach($memberList as $member):?>
                            <tr>
                                <th scope="row"><?= $i+$awalDataMember;?></th>
                                <td><?= $member["firstname"] . " " . $member["lastname"];?>
                                </td>
                                <td>@<?= $member["username"];?></td>
                                <td><?= $member["positionName"];?></td>
                                <td>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button class="btn btn-primary" type="button"><i
                                                class="bi bi-eye-fill"></i></button>

                                        <?php if(IsGroupOwner($accountID, $groupid)) :?>
                                        <button class="btn btn-danger" type="button"><i class="bi
                                                bi-trash3-fill"></i></button>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                            <?php $i++;?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                        <?php if($jumlahHalamanMember == 0):;
                        else: ?>
                            <?php if($halamanAktifMember == 1):
                                $pageAkhir = $halamanAktifMember+2; $pageAwal=$halamanAktifMember?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php else:
                                if($halamanAktifMember == $jumlahHalamanMember){ $pageAwal=$halamanAktifMember-2; $pageAkhir = $halamanAktifMember;}
                                else{ $pageAwal = $halamanAktifMember-1; $pageAkhir = $halamanAktifMember+1;}?>
                                <li class="page-item"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg; ?>&pageMember=<?= $halamanAktifMember-1;?>&groupid=<?= $groupid;?>">Previous</a></li>
                            <?php endif; ?>
                            <?php if($jumlahHalamanMember == 1 || $jumlahHalamanMember == 2){$pageAwal = 1;$pageAkhir = $jumlahHalamanMember;}?>
                            <?php for($i=$pageAwal; $i<=$pageAkhir ;$i++): ?>
                                <?php if($i == $halamanAktifMember):?>
                                    <li class="page-item active" aria-current="page"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg; ?>&pageMember=<?= $i;?>&groupid=<?= $groupid;?>"><?= $i;?></a></li>
                                <?php else:?>
                                    <li class="page-item"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg; ?>&pageMember=<?= $i;?>&groupid=<?= $groupid;?>"><?=$i?></a></li>
                                <?php endif;?>
                            <?php endfor; ?>
                            <?php if($halamanAktifMember == $jumlahHalamanMember):?>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            <?php else:?>
                                <li class="page-item"><a class="page-link" href="?pageAsg=<?= $halamanAktifAsg; ?>&pageMember=<?= $halamanAktifMember+1?>&groupid=<?= $groupid;?>">Next</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                        </ul>
                    </nav>
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

</html>