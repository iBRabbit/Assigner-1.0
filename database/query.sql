use seproject

-- Buat coret coret doang --

SELECT * FROM groups;

SELECT * FROM accounts_groups;

SELECT * FROM positions;

SELECT p.positionName FROM positions p 
JOIN accounts_groups ag
ON ag.groupID = p.groupID
WHERE p.groupID = 1 AND p.positionValue = 1;

SELECT * FROM accounts_groups ag
JOIN groups g
ON g.groupID = ag.groupID
JOIN positions p
ON g.groupID = p.groupID
WHERE ag.accountID = 2;

SELECT * from accounts_groups ag
JOIN positions p
ON p.positionID = ag.positionID
WHERE ag.groupID = 27 AND ag.accountID = 2

SELECT
* 
FROM positions pos
JOIN accounts_groups ag
ON ag.groupID = pos.groupID
WHERE ag.accountID = 2

SELECT ag.accountID FROM accounts_groups ag
JOIN positions pos
ON ag.positionID = pos.positionID
WHERE ag.groupID = 5 AND pos.positionValue = 1;

SELECT * FROM accounts_groups ag
JOIN accounts ac
ON ac.accountID = ag.accountID
JOIN positions pos
ON pos.positionID = ag.positionID
WHERE ag.groupID = 1

INSERT INTO `accounts_groups` (`accountID`, `groupID`, `positionID`) VALUES ('2' , '34' , '23');

SELECT *
FROM positions pos
JOIN accounts_groups ag
ON ag.positionID = pos.positionID
WHERE ag.accountID = 2 AND ag.groupID = 3

INSERT INTO `assignments` (`assignmentID`, `groupID`, `assignmentTitle`, `assignmentDescription`, `assignmentCreated`, `assignmentDeadline`, `assignedTo`, `assignmentStatus`) VALUES (NULL, '1', 'Tugas Video SE', 'SE 1\r\n-> Buat video tentang produk\r\n-> Buat Prototype\r\n-> Selesaikan Wesbite\r\n\r\nSE 2\r\n-> Apa guna app ini?', '2022-03-25', '2022-03-31', '2', '0');

DELETE FROM assignments WHERE assignmentTitle = "";

SELECT * FROM assignments WHERE assignmentID = 23;

describe assignments

SELECT * FROM assignments WHERE assignedTo = 0;

SELECT * FROM assignments WHERE assignmentID = 4;

CREATE TABLE invites (
    inviteID INT(12) NOT NULL AUTO_INCREMENT,
    inviteFrom INT(12) NOT NULL,
    accountID INT(12) NOT NULL,

    CONSTRAINT PK_invites PRIMARY KEY(inviteID),
    CONSTRAINT FK_Invites FOREIGN KEY (accountID) REFERENCES accounts(accountID)
)

describe invites

SELECT * FROM invites;

DELETE FROM invites;

SELECT * FROM invites WHERE accountID = 19 AND inviteGroupID = 1; 

ALTER TABLE notifications ADD notificationOpened INT(12) DEFAULT 0;

describe notifications

select * from notifications

SELECT * FROM invites WHERE inviteGroupID = 1 AND accountID = 0;

SELECT * FROM positions WHERE positionID = 24;