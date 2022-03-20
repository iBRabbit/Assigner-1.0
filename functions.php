<?php 

$connectionID = new mysqli("localhost","root","","seproject");

if ($connectionID -> connect_errno) {
    echo "Failed to connect to MySQL: " . $connectionID -> connect_error;
    exit();
} 

function validate

?>