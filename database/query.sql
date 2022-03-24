use seproject

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
WHERE ag.groupID = 5 AND ag.accountID = 0

SELECT
* 
FROM positions pos
JOIN accounts_groups ag
ON ag.groupID = pos.groupID
WHERE ag.accountID = 2