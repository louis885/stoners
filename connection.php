<?php
//connection to database
$dbhostname =  'localhost';
$dbdatabase = 'db';
$dbuser ='root';
$dbpass = '';

$conn = new mysqli($dbhostname, $dbuser, $dbpass, $dbdatabase);
if($conn ->connect_error){
    die("Could not connect to DB Sever on $dbhostname".$conn->connect_error);
}else{
    echo 'connected to the database';
}
?>