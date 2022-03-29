<?php 

$connectionID = mysqli_connect("localhost","root","","seproject");

if ($connectionID -> connect_errno) {
    echo "Failed to connect to MySQL: " . $connectionID -> connect_error;
    exit();
} 
// -- SQL Functions -- //
function StartLoginSession(){
    session_start();
    if(!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }
}

function Query($query){
    global $connectionID;
    $result = mysqli_query($connectionID, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

// -- SQL Functions -- //


// -- User Functions -- //

function GetUserData($username){
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE username = '$username'");
    $userdata = mysqli_fetch_assoc($result);
    return $userdata;
}

function GetUsernameByID($uid){
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE accountID = $uid");
    $row = mysqli_fetch_assoc($result);
    
    return (mysqli_num_rows($result) > 0) ? $row["username"] : NULL;
}

function GetUserFullName($userid) {
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE accountID = $userid");
    $row = mysqli_fetch_assoc($result);
    
    return (mysqli_num_rows($result) > 0) ? $row["firstname"] . " " . $row["lastname"] : NULL;
}

// return -1 jika tidak ada, return index jika ada.
function IsUsernameExists($username) {
    global $connectionID;
    
    $result = mysqli_query($connectionID, "SELECT * FROM accounts WHERE username = '$username'");
    if(mysqli_num_rows($result) <= 0) return -1;
    $row = mysqli_fetch_assoc($result);

    return $row["accountID"];
}

// -- User Functions -- //

// -- Group Functions -- //

function GetGroupDataByUID($uid){
    $groups = Query("
    SELECT 
    *
    FROM groups g
    JOIN accounts_groups ag
    ON g.groupID = ag.groupID
    WHERE ag.accountID = $uid
    ");

    return $groups;
    
}

function GetGroupNameByID($gid) {
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM groups WHERE groupID = $gid");
    $row = mysqli_fetch_assoc($result);
    return (mysqli_num_rows($result) > 0) ? $row["groupName"] : NULL;
}

function GetMemberListByGroupID($gid) {
    
    $memberList = Query(
        "SELECT * FROM accounts_groups ag
        JOIN accounts ac
        ON ac.accountID = ag.accountID
        JOIN positions pos
        ON pos.positionID = ag.positionID
        WHERE ag.groupID = $gid
    ");
    
    return $memberList;
}

function IsGroupOwner($userid, $groupid){
    global $connectionID;

    $posdata = Query(
        "SELECT *
        FROM positions pos
        JOIN accounts_groups ag
        ON ag.positionID = pos.positionID
        WHERE ag.accountID = $userid AND ag.groupID = $groupid");

    if($posdata[0]["positionValue"] == 1)
        return true;

    return false;
}

function IsGroupMember($userid, $groupid){
    global $connectionID;

    $result = mysqli_query($connectionID, "SELECT *
    FROM positions pos
    JOIN accounts_groups ag
    ON ag.positionID = pos.positionID
    WHERE ag.accountID = $userid AND ag.groupID = $groupid");
    $row = mysqli_fetch_assoc($result);

    return (mysqli_num_rows($result) > 0) ? true : false;
}

function GetGroupOwnerID($groupid){
    global $connectionID;

    $result = Query("
        SELECT ag.accountID as `id` FROM accounts_groups ag
        JOIN positions pos
        ON ag.positionID = pos.positionID
        WHERE ag.groupID = $groupid AND pos.positionValue = 1;
    ");

    return $result[0]["id"];
}

function GetAllGroupPositions($gid) {
    global $connectionID;
    $result = mysqli_query($connectionID, "SELECT * FROM positions WHERE groupID = $gid");
    return $result;
}

function GetUserPositionInGroup($userid, $groupid){
    global $connectionID;

    $positions = Query("SELECT * from accounts_groups ag
    JOIN positions p
    ON p.positionID = ag.positionID
    WHERE ag.groupID = $groupid AND ag.accountID = $userid");
    
    return $positions[0]["positionName"];
}

// -- Group Functions -- //

// -- Assignments Functions -- //

function AddAssignment($members, $groupid, $title, $description, $createdOn, $deadline, $isgroup = false) {

    global $connectionID;
    $myquery = Query("SELECT AUTO_INCREMENT as `AI`
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'seproject'
    AND TABLE_NAME = 'assignments'");
    $asgid = $myquery[0]["AI"];

    mysqli_query($connectionID, "INSERT INTO assignments VALUES ('', '$groupid', '$title', '$description', '$createdOn', '$deadline', '$isgroup');");

    if(!$isgroup) 
        return mysqli_query($connectionID, "INSERT INTO asg_member VALUES ('', '$asgid', '$members', '0')");
    
    
    foreach($members as $mem) 
        mysqli_query($connectionID, "INSERT INTO asg_member VALUES ('', '$asgid', '$mem', '0')");
}

function RemoveAssignment($asgid) {

    global $connectionID;

    mysqli_query($connectionID, "DELETE FROM asg_member WHERE assignmentID = $asgid");

    mysqli_query($connectionID, "DELETE FROM assignments WHERE assignmentID = $asgid");
}

function GetAssignmentListByGroupID($gid) {
    $assignments = Query(    
        "SELECT DISTINCT
        *
        FROM assignments asg
        JOIN groups g 
        ON g.groupID = asg.groupID
        WHERE asg.groupID = '$gid'
        ORDER BY assignmentDeadline ASC" 
    );
    
    return $assignments;
}

function GetStatusValueByID($statusID){
    if($statusID == 1) return 0;
    if($statusID == 2) return 25;
    if($statusID == 3) return 50;
    if($statusID == 4) return 75;
    if($statusID == 5) return 100;
    return NULL;
}

function IsAsgAssignedToID($userid, $asgid){
    global $connectionID;
    
    $result = mysqli_query($connectionID, "SELECT * FROM assignments asg
    JOIN asg_member am
    ON am.assignmentID = asg.assignmentID
    WHERE asg.assignmentID = $asgid");
    $row = mysqli_fetch_assoc($result);

    return ($row["asgMemberAccountID"] == $userid) ? true : false;
}

function GetAllAssignmentsFromID($uid) {
    global $connectionID;

    $result = Query("
    SELECT * FROM assignments asg
    JOIN asg_member am
    ON am.assignmentID = asg.assignmentID
    WHERE am.asgMemberAccountID = $uid
    ORDER BY am.asgMemberProgress ASC, asg.assignmentDeadline ASC
    ");

    return $result;
}

function GetAssignmentMembers($asgid, $isGroup) {

    $result = Query("SELECT * FROM assignments asg
    JOIN asg_member am
    ON am.assignmentID = asg.assignmentID
    WHERE asg.assignmentID = $asgid");

    return ($isGroup) ? $result : $result[0];
}

function CountTotalAssignmentProgress($asgid) {
    $members = GetAssignmentMembers($asgid, true);

    $totalProgress = 100 * count($members);
    $currentProgress = 0;

    foreach($members as $mem)
        $currentProgress += $mem["asgMemberProgress"];
        
    $result = (int) round(($currentProgress / $totalProgress) * 100);
    return $result;
}

// -- Assignments Functions -- //

// -- Invites Functions -- //

function IsAlreadyInvitedToGroup($userid, $groupid) {
    global $connectionID;

    $result = mysqli_query($connectionID, "SELECT * FROM invites WHERE accountID = $userid AND inviteGroupID = $groupid;");  
    
    return (mysqli_num_rows($result) > 0) ? true : false;    
}

// -- invite Functions -- //

// -- Notifications Functions -- //
function GetAllNotifsByUID($uid, $showpartial = 0, $openedonly = 0) {
    
    if(!$showpartial) 
        $result = Query(
            "SELECT * FROM notifications WHERE accountID = $uid
        ");
    else {
        if(!$openedonly) 
            $result = Query(
                "SELECT * FROM notifications WHERE accountID = $uid AND notificationOpened = 0;
            ");
        else {
            $result = Query(
                "SELECT * FROM notifications WHERE accountID = $uid AND notificationOpened = 1;
            ");
        }
    }
        
    
    return $result;
}

function GetUnopenedNotifsSize($accountID){
    global $connectionID;
    $unopenedNotifs = GetAllNotifsByUID($accountID, true);
    $unopenedNotifsSize = count($unopenedNotifs);
    return $unopenedNotifsSize;
}

// -- Notifications Functions -- //

// -- Helper / Validator -- //

function ValidateRegister($input) {
    global $connectionID;
    $username = $input["input-username"];
    $password = mysqli_real_escape_string($connectionID,$input["input-password"]);
    $firstname = mysqli_real_escape_string($connectionID,$input["input-firstname"]);
    $lastname = mysqli_real_escape_string($connectionID,$input["input-lastname"]);

    $check = mysqli_query($connectionID, "SELECT username FROM accounts WHERE username = '$username'");
    if(mysqli_fetch_assoc($check)) {
        echo "<script>alert('Username sudah dipakai')</script>";
        return 0;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $str = "INSERT INTO accounts VALUES ('NULL', '$username','$password', '$firstname', '$lastname')";
    mysqli_query($connectionID,$str);
    return 1;
}

function ValidateGroupLink($userid, $groupid, $header, $owner_only = false){
    if($owner_only) {
        if(!IsGroupOwner($userid, $groupid)){
            $str = "Location:" . $header;
            header($str);
            exit;
        }
    }
    
    if(!IsGroupOwner($userid, $groupid) && !IsGroupMember($userid, $groupid)){
        $str = "Location:" . $header;
        header($str);
        exit;
    }
}

function ValidateAsgLink($userid, $asgid, $groupid, $header){
    
    if(!IsAsgAssignedToID($userid, $asgid) && !IsGroupOwner($userid, $groupid)){
        $str = "Location:" . $header;
        header($str);
        exit;
    }

}

function ValidateRequiredForm($formdata){
    return (empty($formdata)) ? true : false;
}

function Refresh(){
    header("Refresh:0");
}
// -- Helper / Validator -- //