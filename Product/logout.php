<?php
// Initialize the session
session_start();
 
//EXTENSIBILITY --
//if ($idletime > 3mins || isset($_POST["logout"])){
    //execute log out code
    //$_SESSION = array();
    //session_destroy();
    //header("location: login.php");
    //exit;
//}

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>