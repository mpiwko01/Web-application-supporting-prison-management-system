<?php
session_start();
session_unset();
header("Location: logpage.php");  
exit;
?>