<?php
session_start();

if (isset($_SESSION['password_change_try'])) {
    unset($_SESSION['password_change_try']);
}
?>
