<?php
    session_start();
    // Destroy session
    session_destroy(); //destroy the session
    header("location: login.php"); //t
    exit();
?>
