<?php

include_once "includes/functions.php";

$_SESSION = array();

session_destroy();

session_start();
setMessage('success', 'Logout Successfully');

redirect('login.php');
exit();
?>