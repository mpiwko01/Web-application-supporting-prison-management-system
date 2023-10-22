<?php
session_start();

if (isset($_SESSION['prisoner_add'])) {
    unset($_SESSION['prisoner_add']);
}
?>