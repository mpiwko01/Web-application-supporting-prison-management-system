<?php
session_start();
session_unset();
header("Location: ../logpage/logpage.php");  
exit;
?>